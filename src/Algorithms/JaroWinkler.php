<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Algorithms;

final class JaroWinkler implements AlgorithmInterface
{
    private const PREFIX_SCALE = 0.1;

    public function calculate(string $string1, string $string2): float
    {
        $jaro = $this->jaro($string1, $string2);

        if ($jaro === 0.0) {
            return 0.0;
        }

        $prefix = 0;
        $maxLength = min(4, min(strlen($string1), strlen($string2)));

        for ($i = 0; $i < $maxLength; $i++) {
            if ($string1[$i] === $string2[$i]) {
                $prefix++;
            } else {
                break;
            }
        }

        return $jaro + ($prefix * self::PREFIX_SCALE * (1 - $jaro));
    }

    private function jaro(string $string1, string $string2): float
    {
        $len1 = strlen($string1);
        $len2 = strlen($string2);

        if ($len1 === 0 || $len2 === 0) {
            return 0.0;
        }

        $matchDistance = (int) floor(max($len1, $len2) / 2) - 1;
        if ($matchDistance < 0) {
            $matchDistance = 0;
        }

        $string1Matches = array_fill(0, $len1, false);
        $string2Matches = array_fill(0, $len2, false);

        $matches = 0;
        $transpositions = 0;

        for ($i = 0; $i < $len1; $i++) {
            $start = max(0, $i - $matchDistance);
            $end = min($i + $matchDistance + 1, $len2);

            for ($j = $start; $j < $end; $j++) {
                if ($string2Matches[$j] || $string1[$i] !== $string2[$j]) {
                    continue;
                }

                $string1Matches[$i] = true;
                $string2Matches[$j] = true;
                $matches++;
                break;
            }
        }

        if ($matches === 0) {
            return 0.0;
        }

        $k = 0;
        for ($i = 0; $i < $len1; $i++) {
            if (! $string1Matches[$i]) {
                continue;
            }

            while (! $string2Matches[$k]) {
                $k++;
            }

            if ($string1[$i] !== $string2[$k]) {
                $transpositions++;
            }

            $k++;
        }

        return (($matches / $len1) + ($matches / $len2) + (($matches - $transpositions / 2) / $matches)) / 3;
    }

    public function name(): string
    {
        return 'jaro_winkler';
    }
}
