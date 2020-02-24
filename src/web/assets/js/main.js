import 'spectre.css';
import JSONformatter from 'json-formatter-js';

const renderData = (data, target, type) => {
  target = document.getElementById(target);
  target.innerText = '';
  if (typeof data === 'object') {
    const renderer = new JSONformatter(data);
    target.appendChild(renderer.render());
  } else {
    const child = document.createElement(type);
    child.innerText = data;
    target.appendChild(child);
  }
};

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
      const renderer = new JSONformatter(json);
      document.getElementById('data-result').appendChild(renderer.render());
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
    renderData(data, 'partlist', 'li');
    getDataFromAPI(input);
  });
};

document.addEventListener('DOMContentLoaded', e => {
  const fileInput = document.getElementById('file-upload');
  const addParts = document.getElementById('add-parts');

  fileInput.addEventListener('change', e => {
    e.preventDefault;
    getStockFromFile(fileInput);
  });

  addParts.addEventListener('click', e => {
    e.preventDefault;
    console.log(e);
  });
});
