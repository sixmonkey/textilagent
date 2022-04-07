<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ShipmentSchemaTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @return void
     */
    public function test_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('shipments', [
                'id',
                'date',
                'invoice',
            ])
        );
    }

    /**
     *
     * @return void
     */
    public function test_model_can_be_instantiated()
    {
        $shipment = Shipment::factory()->create();

        $this->assertModelExists($shipment);
    }


    /**
     * @return void
     */
    public function test_shipment_has_shipment_items()
    {
        $shipment = Shipment::factory()
            ->hasShipmentItems(3)
            ->create();

        $this->assertEquals(3, $shipment->shipmentItems->count());

        $this->assertInstanceOf(Collection::class, $shipment->shipmentItems);
    }

    /**
     * @return void
     */
    public function test_shipment_has_order_items()
    {
        $shipment = Shipment::factory()
            ->hasAttached(
                OrderItem::factory()->count(12),
                ['amount' => $this->faker->numberBetween(10, 100)]
            )
            ->create();

        $this->assertEquals(12, $shipment->orderItems->count());

        $this->assertInstanceOf(Collection::class, $shipment->orderItems);
    }


    /**
     * @return void
     */
    public function test_order_has_shipments()
    {
        $shipment = Shipment::factory()->create();
        Order::factory()
            ->has(
                OrderItem::factory()
                    ->hasShipmentItems(14, [
                        'shipment_id' => $shipment->id,
                    ])
            )
            ->count(12)
            ->create();

        $this->assertEquals(12, $shipment->orders->count());

        $this->assertInstanceOf(Collection::class, $shipment->orders);
    }

    /**
     * the date of this shipment
     *
     * @return void
     */
    public function test_shipment_date()
    {
        $shipment = Shipment::factory()->create();

        $this->assertInstanceOf(Carbon::class, $shipment->date);
    }
}
