<?php

use App\Models\Company;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->text('contract')->comment('the contract number of the supplier');
            $table->date('date')->comment('the date on the contract');
            $table->boolean('customer_pays')->default(false)->comment('customer pays provision');
            $table->boolean('completed')->default(false)->comment('order is finished');
            $table->foreignIdFor(User::class, 'agent_id')->constrained('users')->cascadeOnDelete()->comment('main agent for the order');
            $table->foreignIdFor(Company::class, 'seller_id')->constrained('companies')->cascadeOnDelete()->comment('supplier for the order');
            $table->foreignIdFor(Company::class, 'purchaser_id')->constrained('companies')->cascadeOnDelete()->comment('supplier of the order');
            $table->foreignIdFor(Currency::class)->constrained('currencies')->cascadeOnDelete()->comment('currency of the order');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
