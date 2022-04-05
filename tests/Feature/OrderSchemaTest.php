<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Schema;
use Tests\TestCase;

class OrderSchemaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders', [
                'id',
                'contract',
                'date',
                'customer_pays',
                'completed',
                'agent_id',
                'customer_id',
                'supplier_id',
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
    public function test_order_belongs_to_currency()
    {
        $currency = Currency::factory()->create();
        $order = Order::factory()
            ->for($currency)
            ->create();

        $this->assertInstanceOf(Currency::class, $order->currency);
    }
}
