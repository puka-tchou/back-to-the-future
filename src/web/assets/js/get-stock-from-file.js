import { getDataFromAPI } from './get-data-from-api';

export const getStockFromFile = (input) => {
  const CSVFile = input.files[0];
  const reader = new FileReader();
  const fragment = new DocumentFragment();
  const target = document.querySelector('#part-table');
  const invalidDataInfo = document.querySelector('#invalid-data-info');
  const partsNumberInfo = document.querySelector('#parts-number-info');
  let isValidData = true;
  let data;

  target.textContent = '';

  performance.mark('start-file');

  return CSVFile.text().then((text) => {
    performance.mark('end-file');
    data = text.split('\r\n').filter((value, index, self) => {
      return self.indexOf(value) === index;
    });

    performance.mark('table-start');

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
      idCell.textContent = index;
      contentCell.textContent = line;
      fragment.append(row);
    }

    target.append(fragment);

    // Performance measurements calls
    console.log('🕵️ file reading has ended');
    performance.mark('table-end');
    performance.mark('end');
    performance.measure('file-reading', 'start-file', 'end-file');
    performance.measure('table', 'table-start', 'table-end');
    console.table(performance.getEntriesByType('measure'));
    performance.clearMarks();
    performance.clearMeasures();

    if (isValidData) {
      partsNumberInfo.textContent = `${data.length} part-numbers in this set.`;
      return getDataFromAPI(input).then((response) => response);
    }
  });
};
