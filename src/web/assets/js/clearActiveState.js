export const clearActiveState = () => {
  const statusMessage = document.getElementById('add-parts-message');
  const statusInfo = document.getElementById('status-info');
  const partsInfo = document.getElementById('add-parts-info');
  const invalidDataInfo = document.getElementById('invalid-data-info');
  const loadingInfo = document.getElementById('loading-info');

  statusMessage.classList.remove('active');
  statusInfo.classList.remove('active');
  partsInfo.classList.remove('active');
  invalidDataInfo.classList.remove('active');
  loadingInfo.classList.remove('active');
};
