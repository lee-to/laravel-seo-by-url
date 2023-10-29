<?php

declare(strict_types=1);

namespace Leeto\Seo\Commands;

use Illuminate\Console\Command;
use MoonShine\MoonShine;

final class MoonShineCommand extends Command
{
    protected $signature = 'seo:moonshine';

    public function handle(): int
    {
        $stub = 'moonshine_seo_resource.stub';

        if($this->confirm('MoonShine v2?')) {
            $stub = 'moonshine_seo_resource_v2.stub';
        }

        $resource = MoonShine::dir('/Resources/SeoResource.php');
        $contents = $this->laravel['files']->get(__DIR__ . '/../../stubs/' . $stub);
        $contents = str_replace('{namespace}', MoonShine::namespace('\Resources'), $contents);

        $this->laravel['files']->put(
            $resource,
            $contents
        );

        $this->components->info('Now register resource in menu');

        return self::SUCCESS;
    }
}
