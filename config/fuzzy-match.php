<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Algorithm
    |--------------------------------------------------------------------------
    |
    | The default algorithm to use for fuzzy matching.
    | Available: 'levenshtein', 'similar_text', 'jaro_winkler'
    |
    */
    'default_algorithm' => env('FUZZY_MATCH_DEFAULT_ALGORITHM', 'levenshtein'),

    /*
    |--------------------------------------------------------------------------
    | Threshold
    |--------------------------------------------------------------------------
    |
    | The threshold for determining similarity.
    | - For Levenshtein: maximum distance (lower is better)
    | - For similarity-based algorithms: minimum similarity score (higher is better)
    |
    */
    'threshold' => env('FUZZY_MATCH_THRESHOLD', 3),

    /*
    |--------------------------------------------------------------------------
    | Case Sensitive
    |--------------------------------------------------------------------------
    |
    | Whether matching should be case sensitive.
    |
    */
    'case_sensitive' => env('FUZZY_MATCH_CASE_SENSITIVE', false),

    /*
    |--------------------------------------------------------------------------
    | Enabled Algorithms
    |--------------------------------------------------------------------------
    |
    | Control which algorithms are available.
    |
    */
    'algorithms' => [
        'levenshtein' => true,
        'similar_text' => true,
        'jaro_winkler' => false,
    ],
];
