<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'contract' => '#' . strtoupper($this->faker->randomLetter) . '-' . $this->faker->numberBetween(1111, 9999),
            'date' => $this->faker->date,
            'customer_pays' => $this->faker->boolean(20),
            'completed' => $this->faker->boolean,
            'agent_id' => User::all()->count() ? User::inRandomOrder()->first()->id : User::factory()->create()->id,
            'seller_id' => Company::all()->count() ? Company::inRandomOrder()->first()->id : Company::factory()->create()->id,
            'purchaser_id' => Company::all()->count() ? Company::inRandomOrder()->first()->id : Company::factory()->create()->id,
            'currency_id' => Currency::all()->count() ? Currency::inRandomOrder()->first()->id : Currency::factory()->create()->id,
        ];
    }
}
