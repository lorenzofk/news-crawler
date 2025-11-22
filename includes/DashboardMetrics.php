<?php

declare(strict_types=1);

/**
 * DashboardMetrics.php
 *
 * Calculates and provides dashboard metrics from a collection of articles.
 */
final class DashboardMetrics
{
    /**
     * Initialises the DashboardMetrics with a collection of articles.
     *
     * @param array<int, array{category: string, title: string, summary: string, url: string, image: string}> $articles An array of articles to analyse.
     */
    public function __construct(
        private readonly array $articles
    ) {}

    /**
     * Returns calculated dashboard metrics for the articles.
     *
     * @return array{most_common_category: string, avg_headline_length: float} An associative array containing the most common category and average headline length.
     */
    public function getMetrics(): array
    {
        return [
            'most_common_category' => $this->getMostCommonCategory(),
            'avg_headline_length' => $this->getAverageHeadlineLength(),
        ];
    }

    /**
     * Calculates the average headline length in words.
     *
     * @return float The average number of words per headline, rounded to 1 decimal place.
     */
    private function getAverageHeadlineLength(): float
    {
        if (empty($this->articles)) {
            return 0.0;
        }

        $totalWords = array_sum(
            array_map(
                fn (array $article): int => str_word_count($article['title'] ?? ''),
                $this->articles
            )
        );

        return round($totalWords / count($this->articles), 1);
    }

    /**
     * Determines the most frequently occurring category.
     *
     * @return string The most common category, or 'N/A' if no categories are found.
     */
    private function getMostCommonCategory(): string
    {
        $categoryCounts = array_count_values(
            array_map(
                fn (array $article): string => $article['category'] ?? 'Unknown',
                $this->articles
            )
        );

        arsort($categoryCounts);

        return array_key_first($categoryCounts) ?? 'N/A';
    }
}
