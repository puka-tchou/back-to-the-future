import XLSX from 'xlsx';

const getDataFromAPI = fileInput => {
  const file = fileInput.files[0];
  const formData = new FormData();
  formData.append('parts', file);

  fetch('http://src.test/api/parts', {
    method: 'POST',
    body: formData
  })
    .then(response => {
      return response.json();
    })
    .then(json => {
      console.debug({ json });
      const body = json['body'];
      let aobj = [];
      const target = document.getElementById('result-table');
      target.innerText = '';
      for (const key in body) {
        if (body.hasOwnProperty(key)) {
          aobj.push(body[key]);

          const row = document.createElement('tr');
          const partCell = row.insertCell();
          const statusCell = row.insertCell();
          const responseCell = row.insertCell();
          const stock = body[key]['body'];

          console.debug(body[key]);

          partCell.innerText = key;
          statusCell.innerText = body[key]['message'];
          responseCell.innerText = JSON.stringify(stock, null, 2);
          target.appendChild(row);
        }
      }
      const sheet = XLSX.utils.json_to_sheet(aobj);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, sheet);
      document
        .getElementById('download-button')
        .addEventListener('click', e => {
          XLSX.writeFile(wb, 'out.xlsx');
        });
    });
};

const getStockFromFile = input => {
  const CSVFile = input.files[0];
  const reader = new FileReader();
  let data;

  reader.readAsText(CSVFile);
  reader.addEventListener('loadend', () => {
    data = reader.result.split('\r\n').filter((value, index, self) => {
      return self.indexOf(value) === index;
    });
    const target = document.getElementById('part-table');
    target.innerText = '';
    data.forEach((line, index) => {
      const row = document.createElement('tr');
      const idCell = row.insertCell();
      const contentCell = row.insertCell();
      idCell.innerText = index;
      contentCell.innerText = line;
      target.appendChild(row);
    });
    getDataFromAPI(input);
  });
};

document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.getElementById('file-upload');
  const getStock = document.getElementById('get-stock');

  fileInput.addEventListener('change', e => {
    e.preventDefault();
    if (fileInput.files.length === 1) {
      getStockFromFile(fileInput);
    }
  });

  getStock.addEventListener('click', e => {
    e.preventDefault();
    if (fileInput.files.length === 1) {
      getStockFromFile(fileInput);
    } else {
      fileInput.click();
    }
  });
});
