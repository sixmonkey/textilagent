<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date,
            'invoice' => '#' . strtoupper($this->faker->randomLetter) . '-' . $this->faker->numberBetween(1111, 9999),
            'seller_id' => Company::all()->count() ? Company::inRandomOrder()->first()->id : Company::factory()->create()->id,
            'purchaser_id' => Company::all()->count() ? Company::inRandomOrder()->first()->id : Company::factory()->create()->id,
        ];
    }
}
