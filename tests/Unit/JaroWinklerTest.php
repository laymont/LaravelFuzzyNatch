<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Tests\Unit;

use Laymont\FuzzyMatch\Algorithms\JaroWinkler;
use Laymont\FuzzyMatch\Tests\TestCase;

it('calculates jaro-winkler similarity correctly', function () {
    $algorithm = new JaroWinkler();

    expect($algorithm->calculate('MAERSK LINE', 'MAERSK LINE'))->toBe(1.0);
    expect($algorithm->calculate('MAERSK LINE', 'MAERSK LINES'))->toBeGreaterThan(0.9);
    expect($algorithm->calculate('MAERSK', 'MAERS'))->toBeGreaterThan(0.8);
    expect($algorithm->calculate('MAERSK', 'MERSK'))->toBeGreaterThan(0.8);
    expect($algorithm->calculate('MARTHA', 'MARHTA'))->toBeGreaterThan(0.9);
    expect($algorithm->calculate('DWAYNE', 'DUANE'))->toBeGreaterThan(0.8);
});

it('returns algorithm name', function () {
    $algorithm = new JaroWinkler();

    expect($algorithm->name())->toBe('jaro_winkler');
});
