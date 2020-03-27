import XLSX from 'xlsx';
import { clearActiveState } from './clearActiveState';

export const getDataFromAPI = (parts) => {
  const resultTable = document.getElementById('result-table');
  const formData = new FormData();

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

      console.log('📋 JSON result below');
      console.log(json);
      resultTable.innerText = '';

      for (const part in body) {
        if (body.hasOwnProperty(part)) {
          const row = document.createElement('tr');
          const partCell = row.insertCell();
          const statusCell = row.insertCell();
          const responseCell = row.insertCell();
          const stock = body[part]['body'];
          let i = 0;

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

      if (i === 0) {
        statusInfo.classList.add('active');
      }

      const sheet = XLSX.utils.json_to_sheet(aobj);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, sheet);
      document
        .getElementById('download-button')
        .addEventListener('click', () => {
          XLSX.writeFile(wb, 'out.xlsx');
        });
    });
};
