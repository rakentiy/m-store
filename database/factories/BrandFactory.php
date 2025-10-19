<?php

namespace Database\Factories;

use App\Models\Brand;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Support\Faker\FakerImageProvider;

/**
 * @extends Factory<Brand>
 * @property Generator&FakerImageProvider $faker
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->company(),
            'thumbnail' => $this->faker->fixturesImages('brands', 'images/brands'),
        ];
    }
}
