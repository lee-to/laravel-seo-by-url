<?php

declare(strict_types=1);

namespace Leeto\Seo\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UrlRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || !preg_match('/^\/[^\s]*$/', $value)) {
            $fail('The :attribute must be a valid url path.');
        }
    }
}
