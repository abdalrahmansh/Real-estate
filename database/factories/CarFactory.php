<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Car;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'model' => fake()->word,
            'color' => fake()->safeColorName,
            'is_new' => fake()->boolean,
            'year' => fake()->year,
            'description' => fake()->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
