import { addPartsFromFile } from './addPartsFromFile';
import { clearActiveState } from './clearActiveState';
import { drawChart } from './drawChart';
import { getStockFromFile } from './getStockFromFile';

document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.getElementById('file-upload');
  const getStock = document.getElementById('get-stock');
  const addParts = document.getElementById('add-parts');
  const loadingInfo = document.getElementById('loading-info');

  console.log("🔥 let's go, I'm ready to rock!");

  // drawChart();

  fileInput.addEventListener('change', (e) => {
    e.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ getting stock records...');
      loadingInfo.classList.add('active');
      getStockFromFile(fileInput);
    }
  });

  getStock.addEventListener('click', (e) => {
    e.preventDefault();
    clearActiveState();
    if (fileInput.files.length === 1) {
      console.log('⏳ getting stock records...');
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
