<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::all()->count() ? Order::inRandomOrder()->first()->id : Order::factory()->create()->id,
            'unit_id' => Unit::all()->count() ? Unit::inRandomOrder()->first()->id : Unit::factory()->create()->id,
            'description' => $this->faker->text(20),
            'typology' => $this->faker->text(20),
            'amount' => $this->faker->numberBetween(100, 1000),
            'etd' => $this->faker->date,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'provision' => $this->faker->randomFloat(2, 10, 30),
        ];
    }
}
