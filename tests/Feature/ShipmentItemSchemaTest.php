<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Schema;
use Tests\TestCase;

class ShipmentItemSchemaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('shipment_items', [
                'id',
                'shipment_id',
                'order_item_id',
                'amount',
            ])
        );
    }

    /**
     *
     * @return void
     */
    public function test_model_can_be_instantiated()
    {
        $shipmentItem = ShipmentItem::factory()->create();

        $this->assertModelExists($shipmentItem);
    }

    /**
     * @return void
     */
    public function test_shipped_item_belongs_to_order_item()
    {
        $orderItem = OrderItem::factory()->create();
        $shippedItem = ShipmentItem::factory()
            ->for($orderItem)
            ->create();

        $this->assertInstanceOf(OrderItem::class, $shippedItem->orderItem);
    }

    /**
     * @return void
     */
    public function test_shipped_item_belongs_to_shipment()
    {
        $shipment = Shipment::factory()->create();
        $shippedItem = ShipmentItem::factory()
            ->for($shipment)
            ->create();

        $this->assertInstanceOf(Shipment::class, $shippedItem->shipment);
    }
}
