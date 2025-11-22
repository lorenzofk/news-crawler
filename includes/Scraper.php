<?php

declare(strict_types=1);

/**
 * Scraper.php
 *
 * Fetches and parses news articles from a specified URL.
 */
final class Scraper
{
    /**
     * Initialises the Scraper with a target URL.
     *
     * @param string $url The URL of the news website to scrape. Defaults to `https://www.news.com.au/`.
     */
    public function __construct(
        private readonly string $url = 'https://www.news.com.au/'
    ) {}

    /**
     * Fetches and parses the latest top news stories.
     *
     * @return array<int, array{category: string, title: string, summary: string, url: string, image: string}> An array of articles, each containing category, title, summary, URL, and image.
     * 
     * @throws \RuntimeException If the HTML cannot be fetched or the DOM cannot be loaded.
     */
    public function getTopStories(): array
    {
        $html = $this->fetchHtml();
        $dom  = $this->loadDom($html);

        $articles = $this->extract($dom);
        
        return $this->normalise($articles);
    }

    /**
     * Cleans and normalises text content.
     *
     * @param string $text The raw text to clean.
     * 
     * @return string The cleaned and normalised text.
     */
    private function cleanText(string $text): string
    {
        $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $decoded = preg_replace('/\s+/', ' ', $decoded);

        return trim($decoded);
    }

    /**
     * Extracts article data from the DOM structure.
     *
     * @param \DOMDocument $dom The parsed DOM document.
     * 
     * @return array<int, array{category: string, title: string, summary: string, url: string, image: string}> An array of raw article data extracted from the DOM.
     */
    private function extract(\DOMDocument $dom): array
    {
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//div[contains(@class,"news-tops_group-")]//article[contains(@class,"storyblock")]');

        $results = [];

        foreach ($nodes as $article) {
            $category = trim($xpath->evaluate('string(.//a[contains(@class,"storyblock_section")])', $article));
            $title = trim($xpath->evaluate('string(.//a[contains(@class,"storyblock_title_link")])', $article));
            $image = trim($xpath->evaluate('string(.//img[contains(@class,"storyblock_img")]/@src)', $article));
            $url = trim($xpath->evaluate('string(.//a[contains(@class,"storyblock_title_link")]/@href)', $article));
            $summary = trim($xpath->evaluate('string(.//a[contains(@class,"storyblock_standfirst_link")])', $article));

            if ($title && $summary) {
                $results[] = compact('category', 'title', 'summary', 'url', 'image');
            }
        }

        return $results;
    }

    /**
     * Fetches the HTML content from the target URL.
     *
     * @return string The raw HTML content from the URL.
     * 
     * @throws \RuntimeException If the cURL handler fails to initialise or if the request fails.
     */
    private function fetchHtml(): string
    {
        $handler = curl_init($this->url);

        if (! ($handler instanceof \CurlHandle)) {
            throw new \RuntimeException('Failed to initialise the cURL handler');
        }

        curl_setopt_array($handler, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $html = curl_exec($handler);

        if ($html === false) {
            throw new \RuntimeException('cURL error: ' . curl_error($handler));
        }

        return $html;
    }

    /**
     * Loads HTML content into a DOMDocument structure.
     *
     * @param string $html The HTML content to parse.
     * 
     * @return \DOMDocument The parsed DOM document.
     */
    private function loadDom(string $html): \DOMDocument
    {
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();

        if (! $dom->loadHTML('<?xml encoding="UTF-8">' . $html)) {
            throw new \RuntimeException('Failed to parse HTML document');
        }

        libxml_clear_errors();

        return $dom;
    }

    /**
     * Normalises and filters article data.
     *
     * @param array<int, array{category: string, title: string, summary: string, url: string, image: string}> $articles The raw article data to normalise.
     * 
     * @return array<int, array{category: string, title: string, summary: string, url: string, image: string}> An array of normalised and filtered articles.
     */
    private function normalise(array $articles): array
    {
        $result = [];

        foreach ($articles as $article) {
            $category = ucfirst($this->cleanText($article['category'] ?: 'Top Story'));

            if (preg_match('/(sponsored|realestate|tubi|trending|you may also like)/i', $category)) {
                continue;
            }

            $title = ucfirst($this->cleanText($article['title']));
            $summary = ucfirst($this->cleanText($article['summary']));

            $result[] = [
                'category' => $category,
                'title'    => $title,
                'summary'  => $summary,
                'url'      => $article['url'],
                'image' => $article['image'] ?: 'https://placehold.co/600x400',
            ];
        }

        return $result;
    }
}
