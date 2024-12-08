<?php

declare(strict_types=1);

namespace Leeto\Seo\Commands;

use Illuminate\Console\Command;

final class MoonShineCommand extends Command
{
    protected $signature = 'seo:moonshine';

    public function handle(): int
    {
        $version = $this->choice(
            'Choose MoonShine version (default v3)',
            [
                1 => 'v1',
                2 => 'v2',
                3 => 'v3',
            ],
            'v3'
        );

        $stub = "moonshine_seo_resource_{$version}.stub";

        if ($version === 'v3') {
            /** @var \MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract $config */
            $config = app(\MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract::class);

            $resource = $config->getDir() . '/Resources/SeoResource.php';
            $namespace = $config->getNamespace('\Resources');
        } else {
            $resource = \MoonShine\MoonShine::dir() . '/Resources/SeoResource.php';
            $namespace = \MoonShine\MoonShine::namespace('\Resources');
        }

        $contents = $this->laravel['files']->get(__DIR__ . "/../../stubs/{$stub}");
        $contents = str_replace('{namespace}', $namespace, $contents);

        $this->laravel['files']->put(
            $resource,
            $contents
        );

        $this->components->info('Now register resource in menu');

        return self::SUCCESS;
    }
}
