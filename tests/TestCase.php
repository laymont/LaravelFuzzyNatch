<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Laymont\FuzzyMatch\FuzzyMatchServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FuzzyMatchServiceProvider::class,
        ];
    }
}
