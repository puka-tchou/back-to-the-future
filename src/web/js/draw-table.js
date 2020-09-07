import { Grid } from 'gridjs';
import 'gridjs/dist/theme/mermaid.css';
import { hideLoading } from './loading';
import { hideAllErrors, showError, logError } from './errors';

export const createTable = (body) => {
	const data = [];
	let i = 0;

	console.log('📋 table creation has started.');
	performance.mark('table-start');

	for (const part in body) {
		if (Object.prototype.hasOwnProperty.call(body, part)) {
			const stock = body[part].body;
			let totalStock = 0;
			let numberOfSuppliers = 0;

			stock.forEach((record) => {
				totalStock += Number(record.parts_in_stock);
				i += totalStock;
				numberOfSuppliers++;
			});

			data.push([part, totalStock, numberOfSuppliers]);
		}
	}

	if (i === 0) {
		logError('no-stock', 'warn');
		showError('no-stock');
		return i;
	}

	const grid = new Grid({
		columns: ['Part number', 'Total stock', 'Number of different suppliers'],
		data,
		sort: true,
		pagination: {
			enabled: true,
			limit: 50,
		},
	});

	const tableContainer = document.querySelector('#data-result');
	tableContainer.innerHTML = '';
	grid.render(tableContainer);

	hideLoading();
	hideAllErrors();
	console.log(`⭕ table creation ended. ${i} stock records found.`);
	performance.mark('table-end');
};
