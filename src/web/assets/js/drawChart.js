import Chart from 'chart.js';

export const drawChart = (data) => {
  const canvas = document.getElementById('data-chart');
  const select = document.getElementById('part-number-select');
  const dates = new Set();
  let stock = {};
  let datasets = [];
  let labels = [];

  const chart = new Chart(canvas, {
    type: 'line',
    data: {
      labels: labels,
      datasets: datasets,
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

  canvas.innerText = '';
  select.innnerText = '';

  if (data !== undefined) {
    for (const partNumber in data) {
      if (data.hasOwnProperty(partNumber)) {
        const option = document.createElement('option');
        option.innerText = partNumber;
        option.value = partNumber;
        select.appendChild(option);
      }
    }

    select.addEventListener('change', (e) => {
      datasets = [];
      labels = [];
      stock = {};

      console.log(`⛏️ part_number selected is ${select.value}`);

      data[select.value]['body'].forEach((record) => {
        const supplier = record['supplier'];
        if (stock[supplier] == undefined) {
          stock[supplier] = [];
        }

        stock[supplier].push(record['parts_in_stock']);
        dates.add(record['date_checked']);
      });

      labels = [...dates];

      for (const label in stock) {
        if (stock.hasOwnProperty(label)) {
          // The lines below are used to generate a unique color based on the
          // label.
          let sum = 0;
          for (let i = 0; i < label.length; i++) {
            sum += label.charCodeAt(i);
          }
          const color = `#${sum.toString(16)}`;

          datasets.push({
            label: label,
            data: stock[label],
            fill: false,
            borderColor: color,
          });
        }
      }

      console.log(labels);
      console.log(datasets);

      chart.data.labels = labels;
      chart.data.datasets = datasets;
      chart.update();
    });
  }
};
