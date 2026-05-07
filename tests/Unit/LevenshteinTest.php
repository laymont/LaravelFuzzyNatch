<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Tests\Unit;

use Laymont\FuzzyMatch\Algorithms\Levenshtein;
use Laymont\FuzzyMatch\Tests\TestCase;

it('calculates levenshtein distance correctly', function () {
    $algorithm = new Levenshtein();

    expect($algorithm->calculate('MAERSK LINE', 'MAERSK LINE'))->toBe(0);
    expect($algorithm->calculate('MAERSK LINE', 'MAERSK LINES'))->toBe(1);
    expect($algorithm->calculate('MAERSK', 'MAERS'))->toBe(1);
    expect($algorithm->calculate('MAERSK', 'MERSK'))->toBe(1);
    expect($algorithm->calculate('MAERSK', 'MAERSK '))->toBe(1);
});

it('returns algorithm name', function () {
    $algorithm = new Levenshtein();

    expect($algorithm->name())->toBe('levenshtein');
});
