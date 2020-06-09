import { clearActiveState } from './clear-active-state';

export const addPartsFromFile = (input) => {
  const formData = new FormData();
  const status = document.querySelector('#add-parts-info');
  const statusMessage = document.querySelector('#add-parts-message');
  const result = document.querySelector('#add-parts-table');

  result.textContent = '';
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
      clearActiveState();
      statusMessage.textContent = json.message;
      for (const key in json.body) {
        if (Object.prototype.hasOwnProperty.call(json.body, key)) {
          const element = json.body[key];
          const row = document.createElement('tr');
          const partCell = row.insertCell();
          const statusCell = row.insertCell();
          let statusText = element.message;
          partCell.textContent = key;
          if (element.body['2'] !== undefined) {
            statusText += ' ' + element.body['2'];
          }

          statusCell.textContent = statusText;
          result.append(row);
        }
      }

      status.classList.add('active');
    });
};
