<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Country;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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
            ])
        );
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

    /**
     * @return void
     */
    public function test_company_belongs_to_country()
    {
        $country = Country::factory()->create();
        $company = Company::factory()
            ->for($country)
            ->create();

        $this->assertInstanceOf(Country::class, $company->country);
    }

    /**
     * @return void
     */
    public function test_company_has_sales()
    {
        $company = Company::factory()
            ->hasSales(3)
            ->create();

        $this->assertEquals(3, $company->sales->count());

        $this->assertInstanceOf(Collection::class, $company->sales);
    }

    /**
     * @return void
     */
    public function test_company_has_purchases()
    {
        $company = Company::factory()
            ->hasPurchases(3)
            ->create();

        $this->assertEquals(3, $company->purchases->count());

        $this->assertInstanceOf(Collection::class, $company->purchases);
    }

    /**
     * @return void
     */
    public function test_company_can_get_country_name()
    {
        $country = Country::factory()->create();
        $company = Company::factory()
            ->for($country)
            ->create();

        $this->assertEquals($country->name, $company->country->name);
    }
}
