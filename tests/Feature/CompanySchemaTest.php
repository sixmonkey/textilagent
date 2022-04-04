<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Schema;
use Tests\TestCase;

class CompanySchemaTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('companies', [
                'id',
                'name',
                'email',
                'phone',
                'address',
                'country_id',
            ]), 1);
    }

    /**
     *
     * @return void
     */
    public function test_model_can_be_instantiated()
    {
        $company = Company::factory()->create();

        $this->assertModelExists($company);
    }
}
