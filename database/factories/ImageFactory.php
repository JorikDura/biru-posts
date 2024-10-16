<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'imageable_id' => $this->faker->randomNumber(),
            'imageable_type' => $this->faker->word(),
            'original_image' => $this->faker->word(),
            'preview_image' => $this->faker->word(),
        ];
    }
}
