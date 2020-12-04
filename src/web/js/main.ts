import 'spectre.css';
import { Api } from './api/api';
import { AppFile } from './input/appFile';
import { Excel } from './output/excel';
import { AppChart } from './userInterface/chart';
import { Dropdown } from './userInterface/dropdown';
import { Table } from './userInterface/table';

document.addEventListener('DOMContentLoaded', () => {
	const api = new Api();
	const table = new Table();
	const dropdown = new Dropdown('#part-number-select');
	const appChart = new AppChart();
	const excel = new Excel();

	const fileInput: HTMLInputElement = document.querySelector('#file-upload');
	const getStock: HTMLButtonElement = document.querySelector('#get-stock');
	const downloadTable: HTMLButtonElement = document.querySelector(
		'#download-button'
	);

	const start = async () => {
		const appFile = new AppFile(fileInput);
		const fileContent = await appFile.read();
		table.draw(
			'#grid-partlist',
			['Id', 'Part-number'],
			10,
			undefined,
			fileContent.csv
		);

		const APIAnswer = await api.query(fileInput);
		dropdown.populate(APIAnswer);
		appChart.draw(APIAnswer);
		table.draw(
			'#data-result',
			['Part number', 'Total stock', 'Number of different suppliers'],
			50,
			APIAnswer
		);

		dropdown.HTMLElement.addEventListener('change', () => {
			appChart.update(APIAnswer, dropdown.HTMLElement.value);
		});

		downloadTable.addEventListener('click', () => {
			excel.newFile(APIAnswer);
		});
	};

	fileInput.addEventListener('change', start);

	getStock.addEventListener('click', start);
});
