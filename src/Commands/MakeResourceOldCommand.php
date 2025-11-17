<?php

declare(strict_types=1);

namespace Leeto\Seo\Commands;

use Illuminate\Console\Command;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;

final class MakeResourceOldCommand extends Command
{
    protected $signature = 'seo:moonshine_old';

    public function handle(): int
    {
        $stub = "moonshine_seo_resource_v3.stub";

        /** @var ConfiguratorContract $config */
        $config = app(ConfiguratorContract::class);

        $resource = $config->getDir() . '/Resources/SeoResource.php';
        $namespace = $config->getNamespace('\Resources');

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
