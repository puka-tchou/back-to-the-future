export const clearActiveState = () => {
	const statusInfo = document.querySelector('#status-info');
	const invalidDataInfo = document.querySelector('#invalid-data-info');
	const loadingInfo = document.querySelector('#loading-info');

	statusInfo.classList.remove('active');
	invalidDataInfo.classList.remove('active');
	loadingInfo.classList.remove('active');
};
