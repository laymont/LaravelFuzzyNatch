<?php

declare(strict_types=1);

namespace Laymont\FuzzyMatch;

use Illuminate\Support\ServiceProvider;
use Laymont\FuzzyMatch\Services\FuzzyMatchService;

final class FuzzyMatchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/fuzzy-match.php',
            'fuzzy-match'
        );

        $this->app->singleton(FuzzyMatchService::class, fn () => new FuzzyMatchService(
            $this->app->make('config'),
        ));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/fuzzy-match.php' => config_path('fuzzy-match.php'),
        ], 'fuzzy-match-config');
    }
}
