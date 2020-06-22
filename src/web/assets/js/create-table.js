import { Grid } from 'gridjs';
import 'gridjs/dist/theme/mermaid.css';
import { clearActiveState } from './clear-active-state';

export const createTable = (body) => {
  const resultTable = document.querySelector('#result-table');
  const resultInfo = document.querySelector('#result-table-info');
  const fragment = document.createDocumentFragment();
  const statusInfo = document.querySelector('#status-info');

  /*
  document.querySelector('#data-result').innerText = '';
  const grid = new Grid({
    columns: ['Name', 'Email', 'Phone Number'],
    data: [
      ['John', 'john@example.com', '(353) 01 222 3333'],
      ['Mark', 'mark@gmail.com', '(01) 22 888 4444'],
    ],
  });
  grid.render(document.querySelector('#data-result'));
  */

  let i = 0;
  performance.mark('table-start');

  resultTable.textContent = '';

  console.log('📋 table creation has started.');
  for (const part in body) {
    if (Object.prototype.hasOwnProperty.call(body, part)) {
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
};
