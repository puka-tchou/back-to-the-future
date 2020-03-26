import { getDataFromAPI } from './getDataFromAPI';

export const getStockFromFile = (input) => {
  const CSVFile = input.files[0];
  const reader = new FileReader();
  const target = document.getElementById('part-table');
  const invalidDataInfo = document.getElementById('invalid-data-info');
  const partsNumberInfo = document.getElementById('parts-number-info');
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
      partsNumberInfo.innerText = `${data.length} part-numbers in this set.`;
      getDataFromAPI(input);
    }
  });
};
