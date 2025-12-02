<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
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
            'description' => fake()->text(),
            'logo' => fake()->imageUrl(640, 480),
            'status' => 'active',
        ];
    }

    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'logo' => null,
        ]);
    }
}
