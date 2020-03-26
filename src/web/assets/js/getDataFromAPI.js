import XLSX from 'xlsx';
import { clearActiveState } from './clearActiveState';
import { drawChart } from './drawChart';

export const getDataFromAPI = (parts) => {
  const target = document.getElementById('result-table');
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
      clearActiveState();
      console.log('📋 JSON result below');
      console.log(json);
      const body = json['body'];
      const aobj = [];
      const datasets = [];
      const dates = new Set();
      target.innerText = '';
      for (const key in body) {
        if (body.hasOwnProperty(key)) {
          const label = new Set();
          const row = document.createElement('tr');
          const partCell = row.insertCell();
          const statusCell = row.insertCell();
          const responseCell = row.insertCell();
          const stock = body[key]['body'];
          const data = [];
          let i = 0;
          aobj.push(body[key]);
          stock.forEach((record) => {
            if (record['state'] === '0' && record['parts_in_stock'] !== '-1') {
              i++;
              dates.add(record['date_checked']);
              data.push(record['parts_in_stock']);
              label.add(record['part_number']);
              partCell.innerText = record['part_number'];
              statusCell.innerText =
                record['state'] === '0' ? i + ' records found.' : '';
              responseCell.innerText += record['parts_in_stock'];
              responseCell.innerText += ', ';
              target.appendChild(row);
            }
          });
          if (data.length > 0) {
            datasets.push({
              label: [...label][0],
              data: data,
              borderColor: '#FFC000',
              pointBackgroundColor: '#4240d4',
              pointBorderColor: '#4240d4',
              fill: false,
            });
          }
        }
      }
      console.log('💽 dataset below');
      console.log(datasets);
      console.log('📆 dates below');
      console.log(dates);
      drawChart([...dates], datasets);
      const sheet = XLSX.utils.json_to_sheet(aobj);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, sheet);
      document
        .getElementById('download-button')
        .addEventListener('click', (e) => {
          XLSX.writeFile(wb, 'out.xlsx');
        });
      const statusInfo = document.getElementById('status-info');
      if (datasets.length === 0) {
        statusInfo.classList.add('active');
      }
    });
};
