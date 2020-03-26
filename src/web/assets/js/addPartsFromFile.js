import { clearActiveState } from './clearActiveState';

export const addPartsFromFile = (input) => {
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
      clearActiveState();
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
