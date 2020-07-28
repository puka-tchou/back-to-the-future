import Chart from 'chart.js';

export const drawChart = (data) => {
  const canvas = document.querySelector('#data-chart');
  const select = document.querySelector('#part-number-select');
  const dates = new Set();
  let stock = {};
  let datasets = [];
  let labels = [];

  const chart = new Chart(canvas, {
    type: 'line',
    data: {
      labels,
      datasets,
    },
    options: {
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
            },
          },
        ],
      },
    },
  });

  canvas.textContent = '';
  select.textContent = '';

  if (data !== undefined) {
    for (const partNumber in data) {
      if (Object.prototype.hasOwnProperty.call(data, partNumber)) {
        const option = document.createElement('option');
        option.textContent = partNumber;
        option.value = partNumber;
        select.append(option);
      }
    }

    select.addEventListener('change', () => {
      datasets = [];
      labels = [];
      stock = {};

      console.log(`⛏️ part_number selected is ${select.value}`);
      performance.mark('chart-start');

      data[select.value].body.forEach((record) => {
        const { supplier } = record;
        if (stock[supplier] === undefined) {
          stock[supplier] = [];
        }

        stock[supplier].push(record.parts_in_stock);
        dates.add(record.date_checked);
      });

      labels = [...dates];

      for (const label in stock) {
        if (Object.prototype.hasOwnProperty.call(stock, label)) {
          // The lines below are used to generate a unique color based on the
          // label.
          let sum = 0;
          for (let i = 0; i < label.length; i++) {
            sum += label.charCodeAt(i);
          }

          const color = `#${sum.toString(16)}`;

          datasets.push({
            label,
            data: stock[label],
            fill: false,
            borderColor: color,
            backgroundColor: color,
            lineTension: 0,
          });
        }
      }

      console.log(
        `📅 dates of the stock history are:\n${JSON.stringify(labels, '', ' ')}`
      );
      console.log('👇 datasets for the chart are below.');
      console.table(datasets);

      chart.data.labels = labels;
      chart.data.datasets = datasets;
      chart.update();

      // Performance measures
      performance.measure('chart-update', 'chart-start');
      console.table(performance.getEntriesByType('measure'));
      performance.clearMarks();
      performance.clearMeasures();
    });
  }
};
