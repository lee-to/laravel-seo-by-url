<?php

declare(strict_types=1);

namespace Leeto\Seo\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Leeto\Seo\Providers\SeoServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithViews;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('cache:clear');

        $this->refreshApplication();
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(realpath('./database/migrations'));

        Factory::guessFactoryNamesUsing(function ($factory) {
            $factoryBasename = class_basename($factory);

            return "Leeto\Seo\Database\Factories\\$factoryBasename".'Factory';
        });
    }

    protected function getPackageProviders($app): array
    {
        return [
            SeoServiceProvider::class,
        ];
    }
}
