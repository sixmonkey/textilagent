<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Order;
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
            ->for($company, 'supplier')
            ->create();

        $this->assertInstanceOf(Company::class, $order->supplier);
    }

    /**
     * @return void
     */
    public function test_order_belongs_to_customer()
    {
        $company = Company::factory()->create();
        $order = Order::factory()
            ->for($company, 'customer')
            ->create();

        $this->assertInstanceOf(Company::class, $order->customer);
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
