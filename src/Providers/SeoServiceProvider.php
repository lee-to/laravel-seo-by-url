<?php

declare(strict_types=1);

namespace Leeto\Seo\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Leeto\Seo\Commands\MoonShineCommand;
use Leeto\Seo\SeoManager;

class SeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/seo.php', 'seo'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../config/seo.php' => config_path('seo.php'),
        ]);

        $this->app->singleton(SeoManager::class);

        Blade::directive('seo', static function ($expression) {
            return '{!! seo() !!}';
        });

        Blade::directive('seoText', static function ($expression) {
            return '{!! seo()->meta()->text('.$expression.') !!}';
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                MoonShineCommand::class,
            ]);
        }
    }
}
