import Chart from 'chart.js';
import { BodyElement, IAPIAnswer } from '../interface/IAPIAnswer';

export class AppChart {
	private _canvas: HTMLCanvasElement;
	private _chart: Chart;
	private _labels: string[];
	private _datasets:
		| Chart.ChartDataSets[]
		| {
				label: string;
				data: number[];
				fill: boolean;
				borderColor: string;
				backgroundColor: string;
				lineTension: number;
		  }[];

	public draw(APIAnswer: IAPIAnswer): void {
		this._canvas = document.querySelector('#data-chart');

		console.debug('first element', Object.keys(APIAnswer.body)[0]);

		this.formatStockData(APIAnswer, Object.keys(APIAnswer.body)[0]);

		this._chart = new Chart(this._canvas, {
			type: 'line',
			data: {
				labels: this._labels,
				datasets: this._datasets,
			},
			options: {
				legend: { position: 'bottom', align: 'start' },
				scales: {
					xAxes: [
						{
							type: 'time',
							time: {
								unit: 'day',
							},
						},
					],
					yAxes: [
						{
							type: 'linear',
						},
					],
				},
			},
		});
	}

	public update(APIAnswer: IAPIAnswer, partNumber: string): void {
		this.formatStockData(APIAnswer, partNumber);

		this._chart.data.labels = this._labels;
		this._chart.data.datasets = this._datasets;
		this._chart.update();
	}

	private formatStockData(APIAnswer: IAPIAnswer, partNumber: string) {
		this._datasets = [];
		this._labels = [];

		const stock = [];
		const dates: Set<string> = new Set();

		APIAnswer.body[partNumber].body.forEach((record: BodyElement) => {
			const { supplier } = record;
			if (stock[supplier] === undefined) {
				stock[supplier] = [];
			}

			stock[supplier].push(record.parts_in_stock);
			dates.add(record.date_checked);
		});

		this._labels = [...dates];

		for (const label in stock) {
			if (Object.prototype.hasOwnProperty.call(stock, label)) {
				const color = this.generateUniqueColor(label);

				this._datasets.push({
					label,
					data: stock[label],
					fill: false,
					borderColor: color,
					backgroundColor: color,
					lineTension: 0,
				});
			}
		}
	}

	private generateUniqueColor(label: string) {
		let sum = 0;
		for (let i = 0; i < label.length; i++) {
			sum += label.charCodeAt(i);
		}

		const color = `#${sum.toString(16)}`;
		return color;
	}
}
