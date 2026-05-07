<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Services;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Laymont\FuzzyMatch\Algorithms\AlgorithmInterface;
use Laymont\FuzzyMatch\Algorithms\JaroWinkler;
use Laymont\FuzzyMatch\Algorithms\Levenshtein;
use Laymont\FuzzyMatch\Algorithms\SimilarText;

final class FuzzyMatchService
{
    private array $algorithms = [];

    public function __construct(
        private readonly ConfigRepository $config,
    ) {
        $this->registerAlgorithms();
    }

    /**
     * Find similar strings in a collection.
     *
     * @param string $needle The string to find matches for
     * @param array $haystack Array of strings to search in
     * @param array $options Configuration options
     * @return array Array of similar strings with their scores
     */
    public function findSimilar(string $needle, array $haystack, array $options = []): array
    {
        $algorithm = $options['algorithm'] ?? $this->config->get('fuzzy-match.default_algorithm', 'levenshtein');
        $threshold = $options['threshold'] ?? $this->config->get('fuzzy-match.threshold', 3);
        $caseSensitive = $options['case_sensitive'] ?? $this->config->get('fuzzy-match.case_sensitive', false);

        if (! $caseSensitive) {
            $needle = strtolower($needle);
            $haystack = array_map('strtolower', $haystack);
        }

        $algorithmInstance = $this->getAlgorithm($algorithm);
        if ($algorithmInstance === null) {
            throw new \InvalidArgumentException("Algorithm '{$algorithm}' not found or not enabled.");
        }

        $results = [];

        foreach ($haystack as $key => $item) {
            $distance = $algorithmInstance->calculate($needle, $item);

            if ($this->isWithinThreshold($algorithm, $distance, $threshold)) {
                $results[] = [
                    'id' => $key,
                    'name' => $item,
                    'distance' => $distance,
                ];
            }
        }

        // Sort by distance (lower is better for Levenshtein, higher is better for similarity-based)
        $this->sortResults($results, $algorithm);

        return $results;
    }

    /**
     * Calculate distance between two strings using a specific algorithm.
     *
     * @param string $string1 First string
     * @param string $string2 Second string
     * @param string $algorithm Algorithm name
     * @return float|int Distance or similarity score
     */
    public function calculateDistance(string $string1, string $string2, string $algorithm): float|int
    {
        $algorithmInstance = $this->getAlgorithm($algorithm);

        if ($algorithmInstance === null) {
            throw new \InvalidArgumentException("Algorithm '{$algorithm}' not found or not enabled.");
        }

        return $algorithmInstance->calculate($string1, $string2);
    }

    private function registerAlgorithms(): void
    {
        $this->algorithms = [
            'levenshtein' => new Levenshtein(),
            'similar_text' => new SimilarText(),
            'jaro_winkler' => new JaroWinkler(),
        ];

        // Filter out disabled algorithms
        $enabledAlgorithms = $this->config->get('fuzzy-match.algorithms', [
            'levenshtein' => true,
            'similar_text' => true,
            'jaro_winkler' => false,
        ]);

        foreach ($enabledAlgorithms as $name => $enabled) {
            if (! $enabled && isset($this->algorithms[$name])) {
                unset($this->algorithms[$name]);
            }
        }
    }

    private function getAlgorithm(string $name): ?AlgorithmInterface
    {
        return $this->algorithms[$name] ?? null;
    }

    private function isWithinThreshold(string $algorithm, float|int $distance, int|float $threshold): bool
    {
        // For Levenshtein, lower distance is better (must be <= threshold)
        // For similarity-based algorithms, higher score is better (must be >= threshold)
        if ($algorithm === 'levenshtein') {
            return $distance <= $threshold;
        }

        // For similarity-based algorithms (similar_text, jaro_winkler), higher is better
        return $distance >= $threshold;
    }

    private function sortResults(array &$results, string $algorithm): void
    {
        if ($algorithm === 'levenshtein') {
            // Lower distance first
            usort($results, fn ($a, $b) => $a['distance'] <=> $b['distance']);
        } else {
            // Higher similarity first
            usort($results, fn ($a, $b) => $b['distance'] <=> $a['distance']);
        }
    }
}
