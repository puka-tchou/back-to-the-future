import Chart from 'chart.js';

export const drawChart = (labels, datasets) => {
  const canvas = document.getElementById('data-chart');
  canvas.innerText = '';
  const stockChart = new Chart(canvas, {
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
};
