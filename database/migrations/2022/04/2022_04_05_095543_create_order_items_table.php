<?php

use App\Models\Order;
use App\Models\Unit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class)->nullable()->constrained('orders')->cascadeOnDelete();
            $table->foreignIdFor(Unit::class)->nullable()->constrained('units')->cascadeOnDelete();
            $table->string('description');
            $table->string('typology');
            $table->unsignedFloat('amount');
            $table->date('etd');
            $table->unsignedFloat('price');
            $table->unsignedFloat('provision');

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
        Schema::dropIfExists('order_items');
    }
}
