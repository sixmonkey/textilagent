<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomElement(['kg', 'mtr', 'pcs', 'l', 'dm', 'qm']),
        ];
    }
}
