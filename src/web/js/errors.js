const errorList = {
	'invalid-data': '🚫 the data provided is not valid.',
	'network-connection':
		'🔌 there was a network error while trying to connect to the API.',
	'no-data-to-download': '🤔 there is no data to download.',
	'no-stock': '🤔 there are no stock informations to show.',
	generic: '🤷 generic error...',
	debug: '🐛 good luck debugging this one.',
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
			try {
				document.querySelector(`#error-${key}`).classList.remove('active');
			} catch (error) {
				console.info(
					`'${key}' error type has no container to display it to the user.`
				);
			}
		}
	}
};

export const logError = (errorName, severity, data) => {
	if (data === undefined) {
		data = '';
	}

	if (errorName in errorList) {
		switch (severity) {
			case 'debug':
				console.trace(`\n${errorList[errorName]}\n${data}`);
				console.log(data);
				break;
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
