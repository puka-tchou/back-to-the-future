import zipcelx from 'zipcelx';
import { IAPIAnswer } from '../interface/IAPIAnswer';

export class Excel {
	public newFile(APIAnswer: IAPIAnswer): void {
		const config = {
			filename: 'stock-report',
			sheet: {
				data: [],
			},
		};

		let offset = 0;
		const dates: Set<string> = new Set();
		const data = [
			// Next array holds the dates
			[
				{ value: '', type: 'string' },
				{ value: '', type: 'string' },
			],
		];

		// First iterate over the part-numbers
		for (const partNumber in APIAnswer.body) {
			if (Object.prototype.hasOwnProperty.call(APIAnswer.body, partNumber)) {
				const records = APIAnswer.body[partNumber];

				// Isolate each dealer so we can assignate
				// each stock value to the correct line
				const dealers: Set<string> = new Set();
				for (const record of records.body) {
					dealers.add(record.supplier);
					dates.add(record.date_checked);
				}

				const dealersArray = [...dealers];

				for (const record of records.body) {
					// Index 0 is for the dates
					const line = dealersArray.indexOf(record.supplier) + 1 + offset;
					if (data[line] === undefined) {
						data.push([]);
					}

					data[line].push({ value: record.parts_in_stock, type: 'number' });
				}

				// Finally, add the dealers and the part-number
				// at the beginning of the data set
				dealersArray.forEach((dealer, index) => {
					// Index 0 is for the dates
					data[index + 1 + offset].unshift(
						{ value: partNumber, type: 'string' },
						{ value: dealer, type: 'string' }
					);
				});

				offset += dealersArray.length;
			}
		}

		dates.forEach((date) => {
			data[0].push({ value: date, type: 'string' });
		});

		config.sheet.data = data;

		zipcelx(config);
	}
}
