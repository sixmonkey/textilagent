<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('date of this shipment');
            $table->string('invoice')->nullable()->comment('unique invoice number by supplier');
            $table->foreignIdFor(Company::class, 'seller_id')->nullable()->constrained('companies')->cascadeOnDelete()->comment('supplier for the shipment');
            $table->foreignIdFor(Company::class, 'purchaser_id')->nullable()->constrained('companies')->cascadeOnDelete()->comment('supplier of the shipment');
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
        Schema::dropIfExists('shipments');
    }
}
