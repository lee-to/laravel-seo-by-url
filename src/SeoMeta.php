<?php

declare(strict_types=1);

namespace Leeto\Seo;

use Leeto\Seo\Models\Seo;
use Stringable;

final class SeoMeta implements Stringable
{
    protected Seo $model;

    protected array $og = [];

    protected string $canonical = '';

    protected array $default = [];

    public function __construct(?Seo $model = null, array $og = [], string $canonical = '')
    {
        $this->model = $model ?? new Seo();
        $this->og = $og;
        $this->canonical = $canonical;
    }

    public static function fromModel(?Seo $model = null): SeoMeta
    {
        return new self($model);
    }

    public static function fromArray(array $data): SeoMeta
    {
        return new self(
            new Seo(collect($data)->except(['og', 'canonical'])->toArray()),
            $data['og'] ?? [],
            $data['canonical'] ?? '',
        );
    }

    public function default(array $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function og(): array
    {
        return $this->og;
    }

    public function canonical(): string
    {
        return $this->canonical;
    }

    public function model(): Seo
    {
        return $this->model;
    }

    public function title(): ?string
    {
        return $this->model()->title ?? $this->default['title'] ?? null;
    }

    public function description(): ?string
    {
        return $this->model()->description ?? $this->default['description'] ?? null;
    }

    public function keywords(): ?string
    {
        return $this->model()->keywords ?? $this->default['keywords'] ?? null;
    }

    public function text(?string $text = null): ?string
    {
        return $this->model()->text
            ?: $text ?? $this->default['text'] ?? null;
    }

    public function html(): string
    {
        $html = str('');

        if ($this->title()) {
            $html = $html->append("<title>{$this->title()}</title>")->append(PHP_EOL);
            $html = $html->append($this->metaTag($this->title(), name: 'title'));
        }

        if ($this->description()) {
            $html = $html->append($this->metaTag($this->description(), name: 'description'));
        }

        if ($this->keywords()) {
            $html = $html->append($this->metaTag($this->keywords(), name: 'keywords'));
        }

        if ($this->og()) {
            foreach ($this->og() as $name => $content) {
                $html = $html->append($this->metaTag($content, property: "og:$name"));
            }
        }

        if ($this->canonical()) {
            $html = $html->append($this->metaTag($this->canonical(), name: 'canonical'));
        }

        return $html->value();
    }

    private function metaTag(string $content, ?string $name = null, ?string $property = null): string
    {
        return "<meta ".($property ? "property='$property'" : "name='$name'")." content='$content'>".PHP_EOL;
    }

    public function __toString(): string
    {
        return $this->html();
    }
}
