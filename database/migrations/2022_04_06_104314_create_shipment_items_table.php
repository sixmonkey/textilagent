<?php

use App\Models\OrderItem;
use App\Models\Shipment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderItem::class)->constrained('order_items')->cascadeOnDelete()->comment('related order item');
            $table->foreignIdFor(Shipment::class)->constrained('shipments')->cascadeOnDelete()->comment('related shipment');
            $table->integer('amount')->comment('how much was shipped');
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
        Schema::dropIfExists('shipment_items');
    }
}
