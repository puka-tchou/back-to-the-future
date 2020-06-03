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
    event.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ getting stock records…');
      loadingInfo.classList.add('active');
      getStockFromFile(fileInput).then((response) => console.log(response));
    }
  });

  getStock.addEventListener('click', (event) => {
    event.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ getting stock records…');
      loadingInfo.classList.add('active');
      getStockFromFile(fileInput).then((response) => console.log(response));
    } else {
      fileInput.click();
    }
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
    console.log('📥 downloading data as an excel file…');
    createExcelFile(JSONresult);
  });
});
