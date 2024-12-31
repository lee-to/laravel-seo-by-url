<?php

declare(strict_types=1);

namespace Leeto\Seo\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Request;
use Leeto\Seo\Models\Seo as SeoModel;

final class SeoFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_basic_usage(): void
    {
        $this->app->instance('request', Request::create('/test'));

        $seo = SeoModel::query()->create([
            'url' => '/test',
            'title' => 'Title',
            'description' => 'Description',
        ]);

        $this->assertEquals($seo->title, seo()->meta()->title());

        $seo->update([
            'title' => 'New title',
        ]);

        $this->assertEquals($seo->title, seo()->meta()->title());

        seo()->title('Custom title');

        $this->assertEquals('Custom title', seo()->meta()->title());
        $this->assertEquals('Description', seo()->meta()->description());
    }

    /**
     * @test
     * @return void
     */
    public function it_basic_usage_with_params(): void
    {
        $this->app->instance('request', Request::create('/test?page=2'));

        $seo = SeoModel::query()->create([
            'url' => '/test?page=2',
            'title' => 'Title',
            'description' => 'Description params',
        ]);

        $this->assertEquals($seo->title, seo()->meta()->title());

        $seo->update([
            'title' => 'New title params',
        ]);

        $this->assertEquals($seo->title, seo()->meta()->title());

        seo()->title('Custom title params');

        $this->assertEquals('Custom title params', seo()->meta()->title());
        $this->assertEquals('Description params', seo()->meta()->description());
    }

    /**
     * @test
     * @return void
     */
    public function it_success_url_generated(): void
    {
        $item = SeoModel::query()->create([
            'url' => 'https://google.com/path',
            'title' => 'Title',
        ]);

        $this->assertEquals('/path', $item->url);

        $item = SeoModel::query()->create([
            'url' => 'https://google.com',
            'title' => 'Title',
        ]);

        $this->assertEquals('/', $item->url);
    }

    /**
     * @test
     * @return void
     */
    public function it_empty_seo(): void
    {
        $this->app->instance('request', Request::create('/empty'));

        $this->assertEquals('', seo()->meta()->title());
    }

    /**
     * @test
     * @return void
     */
    public function it_html_value(): void
    {
        seo()->title('Custom title');

        $this->assertStringContainsString('<title>Custom title</title>', seo()->meta()->html());
    }

    /**
     * @test
     * @return void
     */
    public function it_html_og_value(): void
    {
        seo()->og(['image' => 'test']);

        $this->assertStringContainsString("<meta property='og:image' content='test'>", seo()->meta()->html());
    }
}
