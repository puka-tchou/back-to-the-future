import { addPartsFromFile } from './add-parts-from-file';
import { clearActiveState } from './clear-active-state';
import { getStockFromFile } from './get-stock-from-file';
import 'spectre.css';

document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.querySelector('#file-upload');
  const getStock = document.querySelector('#get-stock');
  const addParts = document.querySelector('#add-parts');
  const loadingInfo = document.querySelector('#loading-info');

  console.log('🔥 let’s go, I’m ready to rock!');

  fileInput.addEventListener('change', (e) => {
    e.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ getting stock records…');
      loadingInfo.classList.add('active');
      getStockFromFile(fileInput);
    }
  });

  getStock.addEventListener('click', (e) => {
    e.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ getting stock records…');
      loadingInfo.classList.add('active');
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
      loadingInfo.classList.add('active');
      addPartsFromFile(fileInput);
    }
  });
});
