export function renderArticles(articles, container) {
    container.innerHTML = "";
  
    const placeholderImage = "https://placehold.co/600x400";

    articles.forEach(article => {
      const column = document.createElement("div");

      const image = article.image || placeholderImage;

      column.innerHTML = `
        <div class="news-card">
          <img src="${image}" class="card-img-top rounded-top" alt="${article.title}" loading="lazy">
          <div class="card-body">
            <span class="badge bg-primary-subtle text-primary fw-semibold mb-2">${article.category}</span>
            <h6 class="fw-bold">${article.title}</h6>
            <p class="text-muted small">${article.summary}</p>
            <a href="${article.url}" target="_blank" class="text-decoration-none fw-semibold">Read more →</a>
          </div>
        </div>`;

      // Append the column to the container
      container.appendChild(column);
    });
  }
  