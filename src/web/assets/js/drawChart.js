import Chart from 'chart.js';

export const drawChart = (data) => {
  const canvas = document.getElementById('data-chart');
  const dates = new Set();
  const datasets = [];
  const stock = {};

  canvas.innerText = '';

  if (data !== undefined) {
    data['BA1R16MV1']['body'].forEach((record) => {
      const supplier = record['supplier'];
      if (stock[supplier] == undefined) {
        stock[supplier] = [];
      }

      stock[supplier].push(record['parts_in_stock']);
      dates.add(record['date_checked']);
    });

    const labels = [...dates];

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

    new Chart(canvas, {
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
  }
};
