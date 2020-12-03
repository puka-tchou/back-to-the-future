import { ISelectElement } from '../interface/ISelectElement';

export class error {
	private errors = {
		'invalid-data': '🚫 the data provided is not valid.',
		'network-connection':
			'🔌 there was a network error while trying to connect to the API.',
		'no-data-to-download': '🤔 there is no data to download.',
		'no-stock': '🤔 there are no stock informations to show.',
		generic: '🤷 generic error...',
		debug: '🐛 good luck debugging this one.',
	};

	private selectElement(errorName: string): ISelectElement {
		let status = 'error';
		const element: HTMLElement = document.querySelector(`#error-${errorName}`);
		let statusMessage = `❌ "${errorName}" is not a valid error name. The error should be one of the following: ${this.errors}`;
		if (element !== null) {
			status = 'valid';
			statusMessage = `🔍 Found selector #error-${errorName}`;
		}
		return {
			status,
			element,
			statusMessage,
		};
	}

	public show(errorName: string): number {
		let returnCode = 1;
		const selector = this.selectElement(errorName);
		if (selector.status === 'valid') {
			selector.element.classList.add('active');
			returnCode = 0;
		} else {
			console.error(selector.statusMessage);
		}
		return returnCode;
	}

	public hide(errorName: string): number {
		let returnCode = 1;
		const selector = this.selectElement(errorName);
		if (selector.status === 'valid') {
			selector.element.classList.remove('active');
			returnCode = 0;
		} else {
			console.error(selector.statusMessage);
		}
		return returnCode;
	}

	public hideAll(): void {
		for (const key in this.errors) {
			if (Object.prototype.hasOwnProperty.call(this.errors, key)) {
				try {
					document.querySelector(`#error-${key}`).classList.remove('active');
				} catch (error) {
					console.info(
						`'${key}' error type has no container to display it to the user.`
					);
				}
			}
		}
	}
}
