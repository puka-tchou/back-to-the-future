import { logError, showError } from './errors';
import { Grid } from 'gridjs';
import { containsForbiddenChar } from './helpers';
import { getDataFromAPI } from './get-data-from-api';

export const readStockFromFile = (input) => {
	const CSVFile = input.files[0];
	let isValidData = true;
	let problematicData;
	let data;

	performance.mark('start-file');

	return CSVFile.text().then((text) => {
		performance.mark('end-file');
		data = text.split('\r\n').filter((value, index, self) => {
			if (containsForbiddenChar(value)) {
				isValidData = false;
				problematicData = { index, value };
			}

			return self.indexOf(value) === index;
		});
		const tableData = [];

		performance.mark('table-start');

		for (const [index, line] of data.entries()) {
			if (line.includes(';')) {
				isValidData = false;
				problematicData = { index, line };
				break;
			}

			tableData.push([index, line]);
		}

		const grid = new Grid({
			columns: ['Id', 'Part number'],
			data: tableData,
			sort: true,
			pagination: {
				enabled: true,
				limit: 10,
			},
		});
		grid.render(document.querySelector('#grid-partlist'));

		// Performance measurements calls
		console.log('🕵️ file reading has ended');
		performance.mark('table-end');
		performance.mark('end');
		performance.measure('file-reading', 'start-file', 'end-file');
		performance.measure('table', 'table-start', 'table-end');
		console.table(performance.getEntriesByType('measure'));
		performance.clearMarks();
		performance.clearMeasures();

		if (isValidData) {
			return getDataFromAPI(input).then((response) => response);
		}

		logError('invalid-data', 'warn', problematicData);
		showError('invalid-data');
	});
};
