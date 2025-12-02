<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->colorName(),
            'sku' => uniqid(),
            'description' => fake()->paragraph(),
            'status' => 'publish',
            'price' => '100',
            'stock_status' => 'instock',
            'quantity' => 100,
            'attribute_set_id' => 1,
            'type_id' => 1,

        ];
    }
}
