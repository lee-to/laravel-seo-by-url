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
        return parse_url($value, PHP_URL_PATH) ?? '/';
    }
}
