import XLSX from 'xlsx';
import Chart from 'chart.js';

const drawChart = (labels, datasets) => {
  const canvas = document.getElementById('data-chart');
  canvas.innerText = '';
  const stockChart = new Chart(canvas, {
    type: 'line',
    data: {
      labels: labels,
      datasets: datasets,
    },
    options: {
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
            },
          },
        ],
      },
    },
  });
};

const getDataFromAPI = (parts) => {
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
              console.log({ record });
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
            console.log({ data });
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
      console.log({ datasets });
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

const getStockFromFile = (input) => {
  const CSVFile = input.files[0];
  const reader = new FileReader();
  const target = document.getElementById('part-table');
  const invalidDataInfo = document.getElementById('invalid-data-info');
  let isValidData = true;
  let data;

  target.innerText = '';
  reader.readAsText(CSVFile);
  reader.addEventListener('loadend', () => {
    data = reader.result.split('\r\n').filter((value, index, self) => {
      return self.indexOf(value) === index;
    });

    for (let index = 0; index < data.length; index++) {
      const line = data[index];
      if (line.includes(';')) {
        isValidData = false;
        console.log('🚫 data is not valid.');
        console.log(data);
        invalidDataInfo.classList.add('active');
        break;
      }
      const row = document.createElement('tr');
      const idCell = row.insertCell();
      const contentCell = row.insertCell();
      idCell.innerText = index;
      contentCell.innerText = line;
      target.appendChild(row);
    }

    if (isValidData) {
      getDataFromAPI(input);
    }
  });
};

const addPartsFromFile = (input) => {
  const formData = new FormData();
  const status = document.getElementById('add-parts-info');
  const statusMessage = document.getElementById('add-parts-message');
  const result = document.getElementById('add-parts-table');

  result.innerText = '';
  formData.append('parts', input.files[0]);

  fetch('http://src.test/api/add', {
    method: 'POST',
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((json) => {
      console.log(json);
      statusMessage.innerText = json.message;
      for (const key in json['body']) {
        if (json['body'].hasOwnProperty(key)) {
          const element = json['body'][key];
          const row = document.createElement('tr');
          const partCell = row.insertCell();
          const statusCell = row.insertCell();
          let statusText = element['message'];

          partCell.innerText = key;
          if (element['body']['2'] !== undefined) {
            statusText += ' ' + element['body']['2'];
          }
          statusCell.innerText = statusText;
          result.appendChild(row);
        }
      }

      status.classList.add('active');
    });
};

const clearActiveState = () => {
  const statusMessage = document.getElementById('add-parts-message');
  const statusInfo = document.getElementById('status-info');
  const partsInfo = document.getElementById('add-parts-info');
  const invalidDataInfo = document.getElementById('invalid-data-info');

  statusMessage.classList.remove('active');
  statusInfo.classList.remove('active');
  partsInfo.classList.remove('active');
  invalidDataInfo.classList.remove('active');
};

document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.getElementById('file-upload');
  const getStock = document.getElementById('get-stock');
  const addParts = document.getElementById('add-parts');

  console.log("🔥 let's go, I'm ready to rock!");

  drawChart();

  fileInput.addEventListener('change', (e) => {
    e.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ getting stock records...');
      getStockFromFile(fileInput);
    }
  });

  getStock.addEventListener('click', (e) => {
    e.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ getting stock records...');
      getStockFromFile(fileInput);
    } else {
      fileInput.click();
    }
  });

  addParts.addEventListener('click', (e) => {
    e.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ adding parts');
      addPartsFromFile(fileInput);
    }
  });
});
