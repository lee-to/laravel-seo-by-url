<?php

declare(strict_types=1);

namespace Leeto\Seo\Commands;

use Illuminate\Console\Command;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;

final class MoonShineCommand extends Command
{
    protected $signature = 'seo:moonshine';

    public function handle(): int
    {
        $stub = 'moonshine_seo_resource.stub';

        /** @var ConfiguratorContract $config */
        $config = app(ConfiguratorContract::class);

        $resource = $config->getDir() . '/Resources/SeoResource.php';

        $contents = $this->laravel['files']->get(__DIR__ . "/../../stubs/{$stub}");
        $contents = str_replace('{namespace}', $config->getNamespace('\Resources'), $contents);

        $this->laravel['files']->put(
            $resource,
            $contents
        );

        $this->components->info('Now register resource in MoonShineServiceProvider');

        return self::SUCCESS;
    }
}
