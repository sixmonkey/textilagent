<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubAgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Order::all()->count() ? Order::inRandomOrder()->first()->id : Order::factory()->create()->id,
            'user_id' => User::all()->count() ? User::inRandomOrder()->first()->id : User::factory()->create()->id,
            'cut' => $this->faker->randomFloat(2, 10, 30),
        ];
    }
}
