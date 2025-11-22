import { renderChart } from './render_charts.js';
import { renderSummary } from './render_summary.js';
import { renderArticles } from './render_articles.js';

document.addEventListener("DOMContentLoaded", async () => {
  const loader = document.getElementById("loader");
  const newsGrid = document.getElementById("news_grid");

  try {
    loader.classList.remove("hidden");

    const response = await fetch("api/scrape.php");

    if (! response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.error) {
      throw new Error(data.message || "An error occurred");
    }

    renderSummary(data);
    renderChart(data.keywords);
    renderArticles(data.articles, newsGrid);

  } catch (error) {
    newsGrid.innerHTML = `<div class="alert alert-danger text-center">Error loading data: ${error.message}</div>`;
  } finally {
    setTimeout(() => loader.classList.add("hidden"), 300);
  }
});
