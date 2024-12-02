<?php

declare(strict_types=1);

namespace Leeto\Seo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Leeto\Seo\Models\Seo;
use Stringable;

/**
 * @method void title(string $value, bool $persist = false)
 * @method void description(string $value, bool $persist = false)
 * @method void keywords(string $value, bool $persist = false)
 * @method void text(string $value, bool $persist = false)
 * @method void og(array $value)
 *
 */
final class SeoManager implements Stringable
{
    protected ?Seo $data;

    protected array $custom = [];

    protected array $persisted = [];

    public function __construct()
    {
        $this->data = $this->cachedByUrl();
    }

    private function data(): ?Seo
    {
        return $this->data;
    }

    private function custom(): array
    {
        return $this->custom;
    }

    public function url(string $url = null): Stringable
    {
        return str($url ?? request()->getRequestUri())
            ->trim('/')
            ->prepend('/');
    }

    public function getCacheKey(string $url = null): string
    {
        return (string) $this->url($url)->slug();
    }

    public function meta(): SeoMeta
    {
        if ($this->custom()) {
            $data = $this->data()
                ? array_merge(Arr::only($this->data()->toArray(), $this->data()->getFillable()), $this->custom())
                : $this->custom();

            return SeoMeta::fromArray($data);
        }

        return SeoMeta::fromModel($this->data())
            ->default(config('seo.default', []));
    }

    public function cachedByUrl(): Model|Seo|null
    {
        $data = cache()->rememberForever(
            $this->getCacheKey(),
            fn() => $this->byUrl() ?? false
        );

        return $data === false ? null : $data;
    }

    public function flushCache(string $key = null): void
    {
        cache()->forget($key ?? $this->getCacheKey());

        $this->data = $this->cachedByUrl();
    }

    public function byUrl(): Model|Seo|null
    {
        if(isset($this->persisted[(string) $this->url()])) {
            return $this->persisted[(string) $this->url()];
        }

        return Seo::query()
            ->where('url', (string) $this->url())
            ->first();
    }

    public function __toString(): string
    {
        return $this->meta()->html();
    }

    public function __call(string $name, array $arguments)
    {
        if (in_array($name, ['title', 'description', 'keywords', 'text', 'og'])) {
            $this->custom[$name] = $arguments[0] ?? '';

            if ($arguments[1] ?? false) {
                $data = Seo::query()->updateOrCreate(
                    ['url' => (string) $this->url()],
                    Arr::except($this->custom, ['og']),
                );

                $this->persisted[$data->url] = $data;
            }
        }
    }
}
