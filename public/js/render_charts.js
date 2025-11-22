export function renderKeywordChart(keywords) {
  const context = document.getElementById("keyword_chart").getContext("2d");
  const labels = Object.keys(keywords);
  const values = Object.values(keywords);

  const gradient = context.createLinearGradient(0, 0, 0, 400);

  // Define the background colour of the chart
  gradient.addColorStop(0, "rgba(37, 99, 235, 0.8)");
  gradient.addColorStop(1, "rgba(37, 99, 235, 0.2)");

  new Chart(context, {
    type: "bar",
    data: {
      labels,
      datasets: [{
        label: "Keyword Frequency",
        data: values,
        backgroundColor: gradient,
        borderColor: "rgba(37,99,235,1)",
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
}

export function renderCategoryChart(categories) {
  const context = document.getElementById("category_chart").getContext("2d");

  new Chart(context, {
    type: "doughnut",
    data: {
      labels: Object.keys(categories),
      datasets: [{
        data: Object.values(categories),
        backgroundColor: [
          "#2563eb", "#10b981", "#f59e0b", "#ef4444", "#8b5cf6", "#0ea5e9",
          "#14b8a6", "#f97316", "#6366f1", "#84cc16"
        ],
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: "60%",
      plugins: {
        legend: { position: "right" },
        datalabels: {
          color: "#fff",
          font: { weight: "bold" },
          formatter: (value, context) => {
            const total = context.chart._metasets[0].total;
            const percent = ((value / total) * 100).toFixed(1);
            return percent + "%";
          }
        }
      }
    },
    plugins: [ChartDataLabels],
  });
}