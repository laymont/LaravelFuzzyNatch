<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Algorithms;

final class SimilarText implements AlgorithmInterface
{
    public function calculate(string $string1, string $string2): float
    {
        similar_text($string1, $string2, $percent);

        return $percent;
    }

    public function name(): string
    {
        return 'similar_text';
    }
}
