import { logError, showError } from './errors';
import DBConfig from '../dbconfig.json';
import { createTable } from './draw-table';
import { drawChart } from './draw-chart';

export const getDataFromAPI = (parts) => {
	const formData = new FormData();
	const DBAdress = DBConfig.db_address;

	performance.mark('start');

	formData.append('parts', parts.files[0]);
	console.log('👋 querying API…');
	return fetch(DBAdress, {
		method: 'POST',
		body: formData,
	})
		.then((response) => {
			logError('debug', 'debug', response);
			if (response.ok) {
				return response.json();
			}

			logError('network-connection', 'warn', response);
			showError('network-connection');
		})
		.then((json) => {
			const { body } = json;

			console.log(`🚅 API returned a response:`);
			console.log(json);
			performance.mark('api-end');

			createTable(body);

			drawChart(body);

			// Performance measurements calls
			console.log('⌛ data processing has ended');
			performance.mark('end');
			performance.measure('api', 'start', 'api-end');
			performance.measure('table', 'table-start', 'table-end');
			performance.measure('total', 'start', 'end');
			console.table(performance.getEntriesByType('measure'));
			performance.clearMarks();
			performance.clearMeasures();

			return json;
		})
		.catch((error) => {
			console.log('Hmmmm');
			logError('debug', 'debug', error);
		});
};
