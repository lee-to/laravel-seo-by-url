<?php

declare(strict_types=1);

namespace factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Leeto\Seo\Models\Seo;

/**
 * @extends Factory<Seo>
 */
class SeoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->word()),
            'description' => $this->faker->text(100),
            'keywords' => $this->faker->text(100),
            'text' => $this->faker->text(100),
        ];
    }
}
