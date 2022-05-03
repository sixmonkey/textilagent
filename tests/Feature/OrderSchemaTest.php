<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
                'purchaser_id',
                'seller_id',
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

    /**
     * @return void
     */
    public function test_order_belongs_to_agent()
    {
        $user = User::factory()->create();
        $order = Order::factory()
            ->for($user, 'agent')
            ->create();

        $this->assertInstanceOf(User::class, $order->agent);
    }

    /**
     * @return void
     */
    public function test_order_belongs_to_supplier()
    {
        $company = Company::factory()->create();
        $order = Order::factory()
            ->for($company, 'purchaser')
            ->create();

        $this->assertInstanceOf(Company::class, $order->purchaser);
    }

    /**
     * @return void
     */
    public function test_order_belongs_to_customer()
    {
        $company = Company::factory()->create();
        $order = Order::factory()
            ->for($company, 'seller')
            ->create();

        $this->assertInstanceOf(Company::class, $order->seller);
    }

    /**
     * @return void
     */
    public function test_order_has_order_items()
    {
        $order = Order::factory()
            ->hasOrderItems(3)
            ->create();

        $this->assertEquals(3, $order->orderItems->count());

        $this->assertInstanceOf(Collection::class, $order->orderItems);
    }

    /**
     * @return void
     */
    public function test_order_has_shipment_items()
    {
        $order = Order::factory()->create();

        OrderItem::factory()
            ->hasShipmentItems(3)
            ->create([
                'order_id' => $order->id
            ]);

        $this->assertEquals(3, $order->shipmentItems->count());

        $this->assertInstanceOf(Collection::class, $order->shipmentItems);
    }

    /**
     * @return void
     */
    public function test_order_has_shipments()
    {
        $order = Order::factory()
            ->has(
                OrderItem::factory()
                    ->has(
                        ShipmentItem::factory()
                            ->for(Shipment::factory()->create())
                            ->count(1)
                    )
                    ->has(
                        ShipmentItem::factory()
                            ->for(Shipment::factory()->create())
                            ->count(3)
                    )
                    ->has(
                        ShipmentItem::factory()
                            ->for(Shipment::factory()->create())
                            ->count(13)
                    )
                    ->count(3)
            )
            ->create();

        $this->assertEquals(3, $order->shipments->count());

        $this->assertInstanceOf(Collection::class, $order->shipments);
    }

    /**
     * the total value of this order
     *
     * @return void
     */
    public function test_order_total_attribute()
    {
        $order = Order::factory()->create();

        $this->assertEquals(0, $order->total);

        $order = Order::factory()
            ->hasOrderItems(5, [
                'amount' => 10,
                'price' => 10,
            ])
            ->create();

        $this->assertEquals(500, $order->total);
    }

    /**
     * the date of this order
     *
     * @return void
     */
    public function test_order_date()
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(Carbon::class, $order->date);
    }
}
