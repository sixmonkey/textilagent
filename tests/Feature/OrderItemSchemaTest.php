<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
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
     * @return void
     */
    public function test_order_item_has_shipment_items()
    {
        $orderItem = OrderItem::factory()
            ->hasShipmentItems(3)
            ->create();

        $this->assertEquals(3, $orderItem->shipmentItems->count());

        $this->assertInstanceOf(Collection::class, $orderItem->shipmentItems);
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

    /**
     * the amount left for this item
     *
     * @return void
     */
    public function test_order_item_amount_left_attribute()
    {
        $order = OrderItem::factory()->create([
            'amount' => 1000
        ]);

        $this->assertEquals(1000, $order->amount_left);

        $orderItem = OrderItem::factory()
            ->hasShipmentItems(5, [
                'amount' => 100,
            ])
            ->create([
                'amount' => 1000
            ]);

        $this->assertEquals(500, $orderItem->amount_left);
    }

    /**
     * the amount left for this item
     *
     * @return void
     */
    public function test_order_item_amount_left_fixed_attribute()
    {
        $order = OrderItem::factory()->create([
            'amount' => 1000
        ]);

        $this->assertEquals(1000, $order->amount_left_fixed);

        $orderItem = OrderItem::factory()
            ->hasShipmentItems(5, [
                'amount' => 1000,
            ])
            ->create([
                'amount' => 1000
            ]);

        $this->assertEquals(0, $orderItem->amount_left_fixed);
    }
}
