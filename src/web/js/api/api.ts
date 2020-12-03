import DBConfig from '../../dbconfig.json';
import { AppFile } from '../input/appFile';
import { IAPIAnswer } from '../interface/IAPIAnswer';

export class Api {
	private _json: IAPIAnswer;

	public get json(): IAPIAnswer {
		return this._json;
	}

	public async query(fileInput: HTMLInputElement): Promise<IAPIAnswer> {
		const formData = new FormData();
		const DBAdress = DBConfig.db_address;
		let json: IAPIAnswer;

		if (fileInput.files.length === 1) {
			const file = new AppFile(fileInput);
			formData.append('parts', file.file);

			const response = await fetch(DBAdress, {
				method: 'POST',
				body: formData,
			});

			if (response.ok) {
				json = await response.json();
			}
		} else {
			fileInput.click();
		}

		this._json = json;
		return json;
	}
}
