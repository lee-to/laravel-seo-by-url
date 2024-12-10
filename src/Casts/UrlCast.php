<?php

declare(strict_types=1);

namespace Leeto\Seo\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class UrlCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        $parsedUrl = parse_url((string)$value);

        $path = isset($parsedUrl['path']) ? '/' . ltrim($parsedUrl['path'], '/') : '/';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

        return $path . $query;
    }
}
