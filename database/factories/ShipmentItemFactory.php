<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'shipment_id' => Shipment::all()->count() ? Shipment::inRandomOrder()->first()->id : Shipment::factory()->create()->id,
            'order_item_id' => OrderItem::all()->count() ? OrderItem::inRandomOrder()->first()->id : OrderItem::factory()->create()->id,
            'amount' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
