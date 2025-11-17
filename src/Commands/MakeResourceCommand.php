<?php

declare(strict_types=1);

namespace Leeto\Seo\Commands;

use Illuminate\Console\Command;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;

final class MakeResourceCommand extends Command
{
    protected $signature = 'seo:moonshine';

    public function handle(): int
    {
        /** @var ConfiguratorContract $config */
        $config = app(ConfiguratorContract::class);

        $stubs = __DIR__ . "/../../stubs";
        $dir = $config->getDir('/Resources/Seo');
        $namespace = $config->getNamespace('\Resources\Seo');

        $resourceContents = $this->laravel['files']->get("$stubs/moonshine_seo_resource.stub");
        $resourceContents = str_replace('{namespace}', $namespace, $resourceContents);

        $this->laravel['files']->ensureDirectoryExists($dir);

        $this->laravel['files']->put("$dir/SeoResource.php", $resourceContents);

        $pages = [
            'SeoIndexPage',
            'SeoFormPage',
            'SeoDetailPage',
        ];

        $pagesDir = "$dir/Pages";

        $this->laravel['files']->ensureDirectoryExists($pagesDir);

        foreach ($pages as $page) {
            $snakeName = str($page)->snake()->value();
            $pageStub = "$stubs/moonshine_$snakeName.stub";

            $pageContents = $this->laravel['files']->get($pageStub);
            $pageContents = str_replace('{namespace}', $namespace, $pageContents);

            $this->laravel['files']->put(
                "$pagesDir/$page.php",
                $pageContents
            );
        }

        $this->components->info('Now register resource in menu');

        return self::SUCCESS;
    }
}
