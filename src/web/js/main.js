import 'spectre.css';
import { hideAllErrors, logError } from './errors';
import { hideLoading, showLoading } from './loading';
import { createExcelFile } from './create-excel-file';
//import { mockFetch } from '../tests/fetch.mock';
import { readStockFromFile } from './read-stock-from-file';

if (process.env.NODE_ENV === 'development') {
	//mockFetch();
}

document.addEventListener('DOMContentLoaded', () => {
	const fileInput = document.querySelector('#file-upload');
	const getStock = document.querySelector('#get-stock');
	const downloadTable = document.querySelector('#download-button');
	let JSONresult;

	console.log('🔥 let’s go, I’m ready to rock!');

	fileInput.addEventListener('change', (event) => {
		uploadFile(event, fileInput).then((response) => {
			JSONresult = response;
		});
	});

	getStock.addEventListener('click', (event) => {
		uploadFile(event, fileInput).then((response) => {
			JSONresult = response;
		});
	});

	downloadTable.addEventListener('click', (event) => {
		event.preventDefault();
		if (typeof JSONresult !== 'undefined' && JSONresult.body.length !== 0) {
			console.log('📥 downloading data as an excel file…');
			return createExcelFile(JSONresult.body);
		}

		logError('no-data-to-download', 'warn');
	});
});

const uploadFile = (event, fileInput) => {
	event.preventDefault();
	hideLoading();
	hideAllErrors();
	if (fileInput.files.length === 1) {
		console.log('⏳ getting stock records…');
		showLoading();
		return readStockFromFile(fileInput).then((response) => response);
	}

	fileInput.click();
};
