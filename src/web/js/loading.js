export const showLoading = () => {
	const loadingInfo = document.querySelector('#info-loading');
	loadingInfo.classList.add('active');
};

export const hideLoading = () => {
	const loadingInfo = document.querySelector('#info-loading');
	loadingInfo.classList.remove('active');
};
