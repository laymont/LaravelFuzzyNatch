<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Tests\Unit;

use Illuminate\Config\Repository as ConfigRepository;
use Laymont\FuzzyMatch\Services\FuzzyMatchService;
use Laymont\FuzzyMatch\Tests\TestCase;

it('finds similar strings using levenshtein', function () {
    $config = new ConfigRepository([
        'fuzzy-match' => [
            'default_algorithm' => 'levenshtein',
            'threshold' => 3,
            'case_sensitive' => false,
            'algorithms' => [
                'levenshtein' => true,
                'similar_text' => true,
                'jaro_winkler' => false,
            ],
        ],
    ]);

    $service = new FuzzyMatchService($config);
    $haystack = [
        'MAERSK LINE',
        'MAERSK LINES',
        'CMA CGM',
        'HAPAG LLOYD',
    ];

    $results = $service->findSimilar('MAERSK LINE', $haystack);

    expect($results)->toHaveCount(2);
    expect($results[0]['name'])->toBe('maersk line');
    expect($results[0]['distance'])->toBe(0);
    expect($results[1]['name'])->toBe('maersk lines');
    expect($results[1]['distance'])->toBe(1);
});

it('finds similar strings using similar_text', function () {
    $config = new ConfigRepository([
        'fuzzy-match' => [
            'default_algorithm' => 'similar_text',
            'threshold' => 80,
            'case_sensitive' => false,
            'algorithms' => [
                'levenshtein' => true,
                'similar_text' => true,
                'jaro_winkler' => false,
            ],
        ],
    ]);

    $service = new FuzzyMatchService($config);
    $haystack = [
        'MAERSK LINE',
        'MAERSK LINES',
        'CMA CGM',
        'HAPAG LLOYD',
    ];

    $results = $service->findSimilar('MAERSK LINE', $haystack);

    expect($results)->toHaveCount(2);
    expect($results[0]['name'])->toBe('maersk line');
    expect($results[0]['distance'])->toBe(100.0);
});

it('calculates distance using specific algorithm', function () {
    $config = new ConfigRepository([
        'fuzzy-match' => [
            'default_algorithm' => 'levenshtein',
            'threshold' => 3,
            'case_sensitive' => false,
            'algorithms' => [
                'levenshtein' => true,
                'similar_text' => true,
                'jaro_winkler' => false,
            ],
        ],
    ]);

    $service = new FuzzyMatchService($config);

    expect($service->calculateDistance('MAERSK LINE', 'MAERSK LINES', 'levenshtein'))->toBe(1);
});

it('throws exception for unknown algorithm', function () {
    $config = new ConfigRepository([
        'fuzzy-match' => [
            'default_algorithm' => 'levenshtein',
            'threshold' => 3,
            'case_sensitive' => false,
            'algorithms' => [
                'levenshtein' => true,
                'similar_text' => true,
                'jaro_winkler' => false,
            ],
        ],
    ]);

    $service = new FuzzyMatchService($config);

    $service->calculateDistance('MAERSK', 'MAERS', 'unknown');
})->throws(\InvalidArgumentException::class, "Algorithm 'unknown' not found or not enabled.");

it('respects case sensitivity option', function () {
    $config = new ConfigRepository([
        'fuzzy-match' => [
            'default_algorithm' => 'levenshtein',
            'threshold' => 3,
            'case_sensitive' => true,
            'algorithms' => [
                'levenshtein' => true,
                'similar_text' => true,
                'jaro_winkler' => false,
            ],
        ],
    ]);

    $service = new FuzzyMatchService($config);
    $haystack = [
        'MAERSK LINE',
        'maersk line',
        'CMA CGM',
    ];

    $results = $service->findSimilar('MAERSK LINE', $haystack);

    expect($results)->toHaveCount(1);
    expect($results[0]['name'])->toBe('MAERSK LINE');
});

it('filters results by threshold', function () {
    $config = new ConfigRepository([
        'fuzzy-match' => [
            'default_algorithm' => 'levenshtein',
            'threshold' => 1,
            'case_sensitive' => false,
            'algorithms' => [
                'levenshtein' => true,
                'similar_text' => true,
                'jaro_winkler' => false,
            ],
        ],
    ]);

    $service = new FuzzyMatchService($config);
    $haystack = [
        'MAERSK LINE',
        'MAERSK LINES',
        'MAERSK',
        'CMA CGM',
    ];

    $results = $service->findSimilar('MAERSK LINE', $haystack);

    expect($results)->toHaveCount(2);
});
