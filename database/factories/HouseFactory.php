<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\House;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
{
    protected $model = House::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location' => $this->faker->city,
            'floor' => $this->faker->word,
            'space' => $this->faker->numberBetween(100, 500),
            'direction' => $this->faker->word,
            'description' => $this->faker->sentence,
            'room_number' => $this->faker->numberBetween(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
