import { addPartsFromFile } from './add-parts-from-file';
import { clearActiveState } from './clear-active-state';
import { getStockFromFile } from './get-stock-from-file';
import 'spectre.css';
import { createExcelFile } from './create-excel-file';

document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.querySelector('#file-upload');
  const getStock = document.querySelector('#get-stock');
  const addParts = document.querySelector('#add-parts');
  const loadingInfo = document.querySelector('#loading-info');
  const downloadTable = document.querySelector('#download-button');
  let JSONresult;

  console.log('🔥 let’s go, I’m ready to rock!');

  fileInput.addEventListener('change', (event) => {
    uploadFile(event, fileInput, loadingInfo).then(
      (response) => (JSONresult = response)
    );
  });

  getStock.addEventListener('click', (event) => {
    uploadFile(event, fileInput, loadingInfo).then(
      (response) => (JSONresult = response)
    );
  });

  addParts.addEventListener('click', (event) => {
    event.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ adding parts');
      loadingInfo.classList.add('active');
      addPartsFromFile(fileInput);
    }
  });

  downloadTable.addEventListener('click', (event) => {
    event.preventDefault();
    if (JSONresult !== undefined && JSONresult['body'].length === 0) {
      console.log('📥 downloading data as an excel file…');
      return createExcelFile(JSONresult['body']);
    }
    console.log('☣️ there is no data to download');
  });
});

const uploadFile = (event, fileInput, loadingInfo) => {
  event.preventDefault();
  clearActiveState();
  if (fileInput.files.length === 1) {
    console.log('⏳ getting stock records…');
    loadingInfo.classList.add('active');
    return getStockFromFile(fileInput).then((response) => response);
  }

  fileInput.click();
};
