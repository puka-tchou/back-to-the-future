import { Helpers } from '../helpers/helpers';
import { ICSVText } from '../interface/ICSVText';

export class AppFile {
	private fileList: FileList;
	private _file: Blob;
	private containsForbiddenChar;

	constructor(input: HTMLInputElement) {
		this.fileList = input.files;
		if (this.isFilelistValid()) {
			this._file = this.fileList[0];
		} else {
			console.error('❌ You tried to send multiple files.');
		}
		this.containsForbiddenChar = new Helpers().containsForbiddenChar;
	}

	public get file(): Blob {
		return this._file;
	}

	private isFilelistValid(): boolean {
		return this.fileList.length === 1 && this.fileList[0] !== undefined;
	}

	public async read(): Promise<ICSVText> {
		let isValid = true;
		let problematicLine: { index: number; value: string };

		const rawText = await this._file.text();
		const csv = rawText.split('\r\n').filter((value, index, self) => {
			if (this.containsForbiddenChar(value)) {
				isValid = false;
				problematicLine = { index, value };
			}

			return self.indexOf(value) === index;
		});

		return {
			csv,
			isValid,
			problematicLine,
		};
	}
}
