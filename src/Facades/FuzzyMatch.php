<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Facades;

use Illuminate\Support\Facades\Facade;
use Laymont\FuzzyMatch\Services\FuzzyMatchService;

/**
 * @method static array findSimilar(string $needle, array $haystack, array $options = [])
 * @method static float|int calculateDistance(string $string1, string $string2, string $algorithm)
 *
 * @see FuzzyMatchService
 */
final class FuzzyMatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FuzzyMatchService::class;
    }
}
