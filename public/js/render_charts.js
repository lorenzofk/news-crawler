export function renderChart(keywords) {
    const context = document.getElementById("keywordChart").getContext("2d");
    const labels = Object.keys(keywords);
    const values = Object.values(keywords);
  
    const gradient = context.createLinearGradient(0, 0, 0, 400);

    // Define the background color of the chart
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
  