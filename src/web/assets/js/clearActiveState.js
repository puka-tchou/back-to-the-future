export const clearActiveState = () => {
  const statusMessage = document.querySelector('#add-parts-message');
  const statusInfo = document.querySelector('#status-info');
  const partsInfo = document.querySelector('#add-parts-info');
  const invalidDataInfo = document.querySelector('#invalid-data-info');
  const loadingInfo = document.querySelector('#loading-info');

  statusMessage.classList.remove('active');
  statusInfo.classList.remove('active');
  partsInfo.classList.remove('active');
  invalidDataInfo.classList.remove('active');
  loadingInfo.classList.remove('active');
};
