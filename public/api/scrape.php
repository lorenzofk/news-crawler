<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/Scraper.php';
require_once __DIR__ . '/../../includes/KeywordAnalyser.php';
require_once __DIR__ . '/../../includes/DashboardMetrics.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    $url = 'https://www.news.com.au/';

    $scraper = new Scraper($url);
    $articles = $scraper->getTopStories();

    $analyser = new KeywordAnalyser($articles);
    $keywords = $analyser->getTopKeywords(limit: 25);

    $metrics = new DashboardMetrics($articles);
    $dashboardMetrics = $metrics->getMetrics();

    $response = [
        'source' => $url,
        'articles' => $articles,
        'keywords' => $keywords,
        'metrics' => $dashboardMetrics,
        'meta' => buildMetaData()
    ];

    http_response_code(200);
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}


function buildMetaData(): array
{
    return [
        'generated_at' => date('d/m/Y H:i:s'),
        'execution_time' => round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3) . 's',
        'memory_usage' => round(memory_get_peak_usage(true) / 1048576, 2) . 'MB'
    ];
}