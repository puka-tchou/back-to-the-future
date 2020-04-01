import { clearActiveState } from './clear-active-state';
import { drawChart } from './draw-chart';

export const getDataFromAPI = (parts) => {
  const resultTable = document.querySelector('#result-table');
  const fragment = document.createDocumentFragment();
  const formData = new FormData();

  performance.mark('start');

  formData.append('parts', parts.files[0]);
  console.log('👋 querying API…');
  fetch('http://src.test/api/parts', {
    method: 'POST',
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((json) => {
      const statusInfo = document.querySelector('#status-info');
      const body = json.body;
      let i = 0;

      console.log(`🚅 API returned a response: ${json}`);
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

      clearActiveState();
      console.log('⭕ table creation ended');
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
      console.log(performance.getEntriesByType('measure'));
      performance.clearMarks();
      performance.clearMeasures();
    });
};
