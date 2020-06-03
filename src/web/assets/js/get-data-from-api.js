import { clearActiveState } from './clear-active-state';
import { drawChart } from './draw-chart';

export const getDataFromAPI = (parts) => {
  const resultTable = document.querySelector('#result-table');
  const resultInfo = document.querySelector('#result-table-info');
  const fragment = document.createDocumentFragment();
  const formData = new FormData();

  performance.mark('start');

  formData.append('parts', parts.files[0]);
  console.log('👋 querying API…');
  return fetch('http://src.test/api/parts', {
    method: 'POST',
    body: formData,
  })
    .then((response) => {
      if (response.ok) {
        return response.json();
      }

      console.log(response.status);
    })
    .then((json) => {
      const statusInfo = document.querySelector('#status-info');
      const { body } = json;
      let i = 0;

      console.log(`🚅 API returned a response:`);
      console.log(json);
      performance.mark('api-end');
      performance.mark('table-start');

      resultTable.textContent = '';

      console.log('📋 table creation has started.');
      for (const part in body) {
        if (body.hasOwnProperty(part)) {
          const row = document.createElement('tr');
          const partCell = row.insertCell();
          const statusCell = row.insertCell();
          const responseCell = row.insertCell();
          const stock = body[part].body;

          stock.forEach((record) => {
            if (record.state === '0' && record.parts_in_stock !== '-1') {
              i++;
              partCell.textContent = record.part_number;
              statusCell.textContent =
                record.state === '0' ? i + ' records found.' : '';
              responseCell.textContent += record.parts_in_stock;
              responseCell.textContent += ', ';
              fragment.append(row);
            }
          });
        }
      }

      resultTable.append(fragment);
      resultInfo.textContent = `${i} stock records found.`;

      clearActiveState();
      console.log(`⭕ table creation ended. ${i} stock records found.`);
      performance.mark('table-end');

      if (i === 0) {
        statusInfo.classList.add('active');
      }

      drawChart(body);

      // Performance measurements calls
      console.log('⌛ data processing has ended');
      performance.mark('end');
      performance.measure('api', 'start', 'api-end');
      performance.measure('table', 'table-start', 'table-end');
      performance.measure('total', 'start', 'end');
      console.table(performance.getEntriesByType('measure'));
      performance.clearMarks();
      performance.clearMeasures();

      return json;
    })
    .catch((error) => {
      console.log(error);
    });
};
