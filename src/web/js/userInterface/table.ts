import { Grid } from 'gridjs';
import { OneDArray } from 'gridjs/dist/src/types';
import 'gridjs/dist/theme/mermaid.css';
import { IAPIAnswer } from '../interface/IAPIAnswer';

export class Table {
	public draw(
		container: string,
		columns: OneDArray<string>,
		limit: number,
		APIAnswer?: IAPIAnswer,
		rawArray?: string[]
	): void {
		const data =
			APIAnswer !== undefined
				? this.formatApiAnswer(APIAnswer)
				: this.formatRawArray(rawArray);

		const grid = new Grid({
			columns,
			data,
			sort: true,
			pagination: {
				enabled: true,
				limit,
			},
		});

		const tableContainer = document.querySelector(container);
		tableContainer.innerHTML = '';
		grid.render(tableContainer);
	}

	private formatApiAnswer(APIAnswer: IAPIAnswer) {
		const formattedData = [];

		for (const part in APIAnswer.body) {
			if (Object.prototype.hasOwnProperty.call(APIAnswer.body, part)) {
				const stock = APIAnswer.body[part].body;
				let totalStock = 0;
				let numberOfSuppliers = 0;

				stock.forEach((record) => {
					totalStock += Number(record.parts_in_stock);
					numberOfSuppliers++;
				});

				formattedData.push([part, totalStock, numberOfSuppliers]);
			}
		}

		return formattedData;
	}

	private formatRawArray(rawArray: string[]) {
		const formattedData = [];

		for (const [index, line] of rawArray.entries()) {
			formattedData.push([index, line]);
		}

		return formattedData;
	}
}
