<?php

declare(strict_types=1);

namespace Leeto\Seo;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string meta()
 * @method static void title(string $value, bool $persist = false)
 * @method static void description(string $value, bool $persist = false)
 * @method static void keywords(string $value, bool $persist = false)
 * @method static void text(string $value, bool $persist = false)
 * @method static void og(array $value)
 *
 * @see SeoManager
 */
final class Seo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SeoManager::class;
    }
}
