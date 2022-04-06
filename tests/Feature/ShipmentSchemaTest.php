<?php

namespace Tests\Feature;

use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Schema;
use Tests\TestCase;

class ShipmentSchemaTest extends TestCase
{

    use RefreshDatabase;

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
