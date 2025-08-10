<?php

declare(strict_types=1);

namespace Leeto\Seo\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Leeto\Seo\Casts\UrlCast;

/**
 * @property string $url
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $text
 */
class Seo extends Model
{
    use HasFactory;

    protected $table = 'seo';

    protected $fillable = [
        'url',
        'title',
        'description',
        'keywords',
        'text',
    ];

    protected $casts = [
        'url' => UrlCast::class,
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::created(static fn (Seo $model) => $model->flushCache());
        static::updated(static fn (Seo $model) => $model->flushCache());
        static::deleting(static fn (Seo $model) => $model->flushCache());
    }

    public function flushCache(): void
    {
        seo()->flushCache(
            seo()->getCacheKey($this->url)
        );
    }
}
