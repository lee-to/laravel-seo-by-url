<?php

declare(strict_types=1);

namespace Leeto\Seo\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class UrlRule implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        if (! is_string(parse_url($value, PHP_URL_PATH))) {
            $fail('The :attribute must be a valid url path.');
        }
    }
}
