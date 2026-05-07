<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Algorithms;

final class Levenshtein implements AlgorithmInterface
{
    public function calculate(string $string1, string $string2): int
    {
        return levenshtein($string1, $string2);
    }

    public function name(): string
    {
        return 'levenshtein';
    }
}
