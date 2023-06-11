<p align="center">
    <a href="https://laravel.com"><img alt="Laravel 9+" src="https://img.shields.io/badge/Laravel-9+-FF2D20?style=for-the-badge&logo=laravel"></a>
    <a href="https://laravel.com"><img alt="PHP 8+" src="https://img.shields.io/badge/PHP-8+-777BB4?style=for-the-badge&logo=php"></a>
</p>

### Prolog

Seo data is stored in the database in the `seo` table and
is linked to pages based on the url, the url is unique for websites, therefore, the seo in this package is built from it

- Easy to use
- Not tied to entities
- All data is cached relative to url and reset by events on the model

### Installation

```shell
composer require lee-to/laravel-seo-by-url
```
Publish config

```shell
php artisan vendor:publish --provider="Leeto\Seo\Providers\SeoServiceProvider"
```

```shell
php artisan migrate
```

### Are you a visual learner?

We've recorded a [video](https://youtu.be/QjTsC1QF0co) on how to use this package. It's the best way to get started using media library

### MoonShine

if you use the [MoonShine](https://moonshine.cutcode.ru), then publish the resource with this command

```shell
php artisan seo:moonshine
```

### Get started

For starters, you can choose the best usage approach for you:

- Facade
```php
use Leeto\Seo\Seo;

// ...

Seo::title('Hello world')
```

- Helper
```php
seo()->title('Hello world')
```

- DI
```php
use Leeto\Seo\SeoManager;

// ...

public function __invoke(SeoManager $seo)
{
    //
}
```


* Ok I prefer to use the helper

### Blade directives

#### Render meta tags
title, descriptions, keywords, og

```html
<html>
<head>
    <!-- // .. -->

    @seo

    <!-- // .. -->
</head>
```

#### Render seo text

```html
<div>
    @seoText('Default text')
</div>
```

### Set and save seo data

- set

```php
seo()->title('Im page title')
```

- set and save in database

```php
seo()->title('Im page title', true)
```

- other tags

```php
seo()->description('Im page description')
seo()->keywords('Im page description')
seo()->text('Im page description')
seo()->og(['image' => 'link to image'])
```

- get value

```php
seo()->meta()->title()
seo()->meta()->description()
seo()->meta()->keywords()
seo()->meta()->text()
seo()->meta()->og()
```


- get html tags

```php
seo()->meta()->html()
```

- save by model

```php
use Leeto\Seo\Models\Seo;

Seo::create([
    'url' => '/',
    'title' => 'Im title'
]);
```

### Default values

Set in seo config `config/seo.php`

```php
return [
    'default' => [
        'title' => 'Im title'
    ]
]);
```


### Inertia

Use Shared Data

```php
class HandleInertiaRequests extends Middleware
{
    //
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            // ...
            
            'seo' => [
                'title' => seo()->meta()->title(),
                'description' => seo()->meta()->description(),
                'keywords' => seo()->meta()->keywords(),
                'og' => seo()->meta()->og(),
                'text' => seo()->meta()->text(),
            ]
        ]);
    }
    //
}
```

```js
import { Head } from '@inertiajs/vue3'

<Head>
  <title>{{ $page.props.seo.title }}</title>
  <meta name="description" :content="$page.props.seo.description">
</Head>
```

