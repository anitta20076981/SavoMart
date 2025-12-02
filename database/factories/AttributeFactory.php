<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class AttributeFactory extends Factory
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
            'code' => fake()->numberBetween($min = 1000, $max = 2000),
            'input_type' => fake()->randomElement(['dropdown', 'textswatch', 'visualswatch', 'textfield', 'textarea', 'texteditor', 'date', 'datetime', 'yesno']),
            'is_required' => fake()->randomElement(['0', '1']),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
