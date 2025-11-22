import { escapeHtml, escapeUrl } from './utils/helper.js';

export function renderArticles(articles, container) {
    container.innerHTML = "";
  
    const placeholderImage = "https://placehold.co/600x400";

    articles.forEach(article => {
      const column = document.createElement("div");

      const image = article.image || placeholderImage;

      column.innerHTML = `
        <div class="news-card">
          <img src="${escapeUrl(image)}" class="card-img-top rounded-top" alt="${escapeHtml(article.title || '')}" loading="lazy">
          <div class="card-body">
            <span class="badge bg-primary-subtle text-primary fw-semibold mb-2">${escapeHtml(article.category || '')}</span>
            <h6 class="fw-bold">${escapeHtml(article.title || '')}</h6>
            <p class="text-muted small">${escapeHtml(article.summary || '')}</p>
            <a href="${escapeUrl(article.url)}" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold">Read more →</a>
          </div>
        </div>`;

      // Append the column to the container
      container.appendChild(column);
    });
  }
  