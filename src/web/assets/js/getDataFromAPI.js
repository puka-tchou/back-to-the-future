import { clearActiveState } from './clearActiveState';
import { drawChart } from './drawChart';

export const getDataFromAPI = (parts) => {
  const resultTable = document.getElementById('result-table');
  const formData = new FormData();

  performance.mark('start');

  formData.append('parts', parts.files[0]);
  fetch('http://src.test/api/parts', {
    method: 'POST',
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((json) => {
      const statusInfo = document.getElementById('status-info');
      const body = json['body'];
      const aobj = [];
      let i = 0;

      console.log(`🚅 API returned a response: ${json['message']}`);
      console.log('📋 JSON result below');
      console.log(json);
      performance.mark('api-end');
      performance.mark('table-start');

      resultTable.innerText = '';

      for (const part in body) {
        if (body.hasOwnProperty(part)) {
          const row = document.createElement('tr');
          const partCell = row.insertCell();
          const statusCell = row.insertCell();
          const responseCell = row.insertCell();
          const stock = body[part]['body'];

          aobj.push(body[part]);

          stock.forEach((record) => {
            if (record['state'] === '0' && record['parts_in_stock'] !== '-1') {
              i++;
              partCell.innerText = record['part_number'];
              statusCell.innerText =
                record['state'] === '0' ? i + ' records found.' : '';
              responseCell.innerText += record['parts_in_stock'];
              responseCell.innerText += ', ';
              resultTable.appendChild(row);
            }
          });
        }
      }

      clearActiveState();
      console.log('⏳ table creation ended');
      performance.mark('table-end');

      if (i === 0) {
        statusInfo.classList.add('active');
      }

      drawChart(body);

      // const sheet = XLSX.utils.json_to_sheet(aobj);
      // const wb = XLSX.utils.book_new();
      // XLSX.utils.book_append_sheet(wb, sheet);
      // document
      //   .getElementById('download-button')
      //   .addEventListener('click', () => {
      //     XLSX.writeFile(wb, 'out.xlsx');
      //   });

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
