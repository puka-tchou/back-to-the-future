const errorList = {
	'invalid-data': '🚫 the data provided is not valid.',
	'network-connection':
		'🔌 there was a network error while trying to connect to the API.',
	'no-data-to-download': '🤔 there is no data to download.',
	'no-stock': '🤔 there are no stock informations to show.',
	generic: '🤷 generic error...',
};

export const showError = (errorName) => {
	if (errorName in errorList) {
		const element = document.querySelector(`#error-${errorName}`);
		element.classList.add('active');
	} else {
		console.error(
			`"${errorName}" is not a valid error name. The error should be one of the following: ${errorList}`
		);
	}
};

export const hideAllErrors = () => {
	for (const key in errorList) {
		if (Object.prototype.hasOwnProperty.call(errorList, key)) {
			document.querySelector(`#error-${key}`).classList.remove('active');
		}
	}
};

export const logError = (errorName, severity, data) => {
	if (data === undefined) {
		data = {};
	}

	if (errorName in errorList) {
		switch (severity) {
			case 'warn':
				console.warn(`${errorList[errorName]}\n${data}`);
				break;
			case 'error':
			default:
				console.error(`${errorList[errorName]}\n${data}`);
				break;
		}
	} else {
		console.error(
			`"${errorName}" is not a valid error identifier. The error identifier should be one of: ${Object.keys(
				errorList
			)}`
		);
	}
};
