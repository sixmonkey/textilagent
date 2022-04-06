<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Schema;
use Tests\TestCase;

class OrderItemSchemaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('order_items', [
                'id',
                'order_id',
                'unit_id',
                'description',
                'typology',
                'amount',
                'etd',
                'price',
                'provision'
            ])
        );
    }

    /**
     *
     * @return void
     */
    public function test_model_can_be_instantiated()
    {
        $order = Order::factory()->create();

        $this->assertModelExists($order);
    }

    /**
     * @return void
     */
    public function test_order_item_belongs_to_order()
    {
        $order = Order::factory()->create();
        $orderItem = OrderItem::factory()
            ->for($order)
            ->create();

        $this->assertInstanceOf(Order::class, $orderItem->order);
    }

    /**
     * @return void
     */
    public function test_order_item_belongs_to_unit()
    {
        $unit = Unit::factory()->create();
        $orderItem = OrderItem::factory()
            ->for($unit)
            ->create();

        $this->assertInstanceOf(Unit::class, $orderItem->unit);
    }

    /**
     * the etd of this item
     *
     * @return void
     */
    public function test_order_item_etd()
    {
        $orderItem = OrderItem::factory()->create();

        $this->assertInstanceOf(Carbon::class, $orderItem->etd);
    }
}
