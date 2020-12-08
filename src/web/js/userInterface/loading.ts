export const showLoading = (): void => {
	const loadingInfo = document.querySelector('#info-loading');
	loadingInfo.classList.add('active');
};

export const hideLoading = (): void => {
	const loadingInfo = document.querySelector('#info-loading');
	loadingInfo.classList.remove('active');
};
