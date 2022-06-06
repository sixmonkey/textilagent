<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            $table->text('name');
            $table->text('official_name');
            $table->string('abbreviation')->nullable();
            $table->string('capital')->nullable();

            $table->string('iso_alpha_2', 3)->unique();
            $table->string('iso_alpha_3', 4);
            $table->smallInteger('iso_numeric')->nullable();

            $table->string('calling_code', 150)->nullable();

            $table->string('tld')->nullable()->comment('Top-level domain');

            $table->string('emoji');

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
        Schema::dropIfExists('countries');
    }
}
