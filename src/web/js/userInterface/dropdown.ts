import SlimSelect from 'slim-select';
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
		const data = [];
		for (const partNumber in APIAnswer.body) {
			if (Object.prototype.hasOwnProperty.call(APIAnswer.body, partNumber)) {
				data.push({ text: partNumber });
			}
		}
		new SlimSelect({
			select: this._HTMLElement,
			data,
		});
	}
}
