<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Algorithms;

interface AlgorithmInterface
{
    /**
     * Calculate the distance/similarity between two strings.
     *
     * @param string $string1 First string
     * @param string $string2 Second string
     * @return float|int Distance or similarity score
     */
    public function calculate(string $string1, string $string2): float|int;

    /**
     * Get the algorithm name.
     *
     * @return string
     */
    public function name(): string;
}
