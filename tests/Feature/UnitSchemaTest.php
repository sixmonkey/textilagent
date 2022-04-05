<?php

namespace Tests\Feature;

use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Schema;
use Tests\TestCase;

class UnitSchemaTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('units', [
                'id',
                'code',
            ]), 1);
    }

    /**
     *
     * @return void
     */
    public function test_model_can_be_instantiated()
    {
        $unit = Unit::factory()->create();

        $this->assertModelExists($unit);
    }
}
