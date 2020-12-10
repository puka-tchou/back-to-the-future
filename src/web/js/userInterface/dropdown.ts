import { IAPIAnswer } from '../interface/IAPIAnswer';

export class Dropdown {
	private _HTMLElement: HTMLSelectElement;

	public get HTMLElement(): HTMLSelectElement {
		return this._HTMLElement;
	}

	constructor(selector: string) {
		this._HTMLElement = document.querySelector(selector);
	}

	public populate(APIAnswer: IAPIAnswer): void {
		this.clear();
		for (const partNumber in APIAnswer.body) {
			if (Object.prototype.hasOwnProperty.call(APIAnswer.body, partNumber)) {
				const option = document.createElement('option');
				option.textContent = partNumber;
				option.value = partNumber;
				this._HTMLElement.append(option);
			}
		}
	}

	public clear(): void {
		this._HTMLElement.innerHTML = '';
	}
}
