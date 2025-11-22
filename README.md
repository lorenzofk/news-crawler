# 📰 News Crawler Dashboard

A lightweight PHP + Vanilla JS application that scrapes the front page of **news.com.au** and transforms it into an interactive dashboard showing article insights, keyword trends, and category distribution.

## ⚡ Overview

**Goal:** Demonstrate full-stack ability by scraping a real news site, processing data on the backend, and visualising it dynamically on the frontend — using only PHP + Vanilla JS (no frameworks).

## Key Features

- **Web scraping** via PHP cURL + DOMDocument
- **Keyword frequency analysis**
- **Category distribution** (donut chart)
- **Live metrics** (average headline length, top category, keyword trends)
- **Article grid** with image, summary, and link
- **Responsive dashboard** with Bootstrap 5 + Chart.js

## 🧱 Project Structure

```
news-crawler/
├── includes/
│   ├── Scraper.php            # Extracts articles from `news.com.au`
│   ├── KeywordAnalyser.php    # Identifies top keywords
│   └── DashboardMetrics.php   # Calculates metrics & category distribution
├── public/
│   ├── api/
│   │   └── scrape.php         # Main API endpoint returning JSON
│   ├── js/
│   │   ├── app.js             # Fetch + render controller
│   │   ├── render_charts.js   # Chart.js visualizations
│   │   ├── render_articles.js # Builds article cards
│   │   └── render_summary.js  # Displays key metrics
│   ├── css/style.css
│   └── index.html
└── README.md
```

## ⚙️ How to Run

### Requirements
- PHP 8.0+
- cURL and DOM extensions enabled

### Steps

```bash
# 1. Start local server
php -S localhost:8000 -t public

# 2. Open in browser
http://localhost:8000
```

## 🧩 How It Works

1. **Scrape:** `Scraper` fetches and parses the front page HTML via cURL + XPath.
2. **Analyse:**
   - `KeywordAnalyser` finds top keywords (excluding stop-words).
   - `DashboardMetrics` computes most common category, avg headline length, and category distribution.
3. **API:** `/api/scrape.php` returns structured JSON.
4. **Visualise:** Frontend renders charts and articles using Chart.js + Bootstrap.

## 📊 Visuals

- **Bar Chart:** Top keywords by frequency
- **Donut Chart:** Category distribution (%)
- **Cards:** Latest headlines + summaries
- **Stats:** Article count | Top keyword | Common category | Average headline length

## 🧠 Tech Stack

- **Backend:** PHP 8 (OOP with strict types), cURL, DOMDocument
- **Frontend:** Vanilla JS (ES6 modules), Chart.js (+ DataLabels plugin), Bootstrap 5

## 👤 Author

**Lorenzo Kniss** - 🔗 [linkedin.com/in/lorenzokniss](https://www.linkedin.com/in/lorenzokniss/)
