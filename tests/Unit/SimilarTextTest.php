<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Tests\Unit;

use Laymont\FuzzyMatch\Algorithms\SimilarText;
use Laymont\FuzzyMatch\Tests\TestCase;

it('calculates similarity text percentage correctly', function () {
    $algorithm = new SimilarText();

    expect($algorithm->calculate('MAERSK LINE', 'MAERSK LINE'))->toBe(100.0);
    expect($algorithm->calculate('MAERSK LINE', 'MAERSK LINES'))->toBeGreaterThan(90.0);
    expect($algorithm->calculate('MAERSK', 'MAERS'))->toBeGreaterThan(80.0);
    expect($algorithm->calculate('MAERSK', 'MERSK'))->toBeGreaterThan(80.0);
});

it('returns algorithm name', function () {
    $algorithm = new SimilarText();

    expect($algorithm->name())->toBe('similar_text');
});
