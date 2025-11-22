<?php

declare(strict_types=1);

/**
 * KeywordAnalyser.php
 *
 * Analyses article text and produces a frequency map of the most common words (excluding stop words).
 */
final class KeywordAnalyser
{
    /**
     * Initialises the KeywordAnalyser with a collection of articles.
     *
     * @param array<int, array{title: string, summary: string}> $articles An array of articles, each containing a title and summary.
     * 
     * @throws \InvalidArgumentException If the articles array is empty.
     */
    public function __construct(
        private readonly array $articles = []
    ) {
        if (empty($articles)) {
            throw new \InvalidArgumentException(message: 'Articles cannot be empty');
        }
    }

    /**
     * Returns the top `N` keywords with their occurrence counts.
     *
     * @param int $limit The maximum number of top keywords to return. Defaults to 10.
     * 
     * @return array<string, int> An associative array where keys are keywords and values are their occurrence counts, sorted by frequency.
     */
    public function getTopKeywords(int $limit = 10): array
    {
        $text = $this->extractText();
        $words = $this->tokenise($text);
        $filtered = $this->filterStopWords($words);

        $counts = array_count_values($filtered);

        arsort($counts);

        return array_slice($counts, 0, $limit, true);
    }

    /**
     * Extracts and concatenates all text from article titles and summaries.
     *
     * @return string A concatenated, lowercase string containing all article titles and summaries.
     */
    private function extractText(): string
    {
        $content = '';

        foreach ($this->articles as $article) {
            $content .= ' ' . ($article['title'] ?? '') . ' ' . ($article['summary'] ?? '');
        }

        return mb_strtolower($content);
    }

    /**
     * Filters out common stop words and short words from the word list to focus on meaningful keywords.
     *
     * @param string[] $words An array of words to filter.
     * 
     * @return string[] An array of filtered words with stop words and short words removed.
     */
    private function filterStopWords(array $words): array
    {
        static $stopWords = [
            'the','a','an','to','of','and','in','on','for','with','is','at','by','from','has',
            'this','that','it','as','be','are','was','were','or','not','but','about','into'
        ];

        return array_values(array_filter(
            $words,
            fn (string $word): bool => ! in_array(needle: $word, haystack: $stopWords, strict: true) && strlen($word) > 2
        ));
    }

    /**
     * Splits text into normalised tokens.
     *
     * Removes all non-alphanumeric characters (except spaces) and splits the text
     * into individual words, filtering out empty strings.
     *
     * @param string $text The text to tokenise.
     * 
     * @return string[] An array of normalised word tokens.
     */
    private function tokenise(string $text): array
    {
        $text = preg_replace('/[^a-zA-Z0-9\s]/u', ' ', $text);

        return array_values(array_filter(explode(' ', $text), fn (string $word): bool => ! empty($word)));
    }
}
