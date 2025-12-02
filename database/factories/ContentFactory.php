<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'title' => fake()->title(),
            'content' => fake()->paragraph(),
            'file' => fake()->imageUrl(640, 480),
            'thumbnail' => fake()->imageUrl(640, 480),
            'slug' => fake()->unique()->slug(),
            'status' => 'active',
            'is_deletable' => array_rand([0, 1]),
        ];
    }
}
