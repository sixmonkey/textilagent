<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CountrySchemaTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /**
     * @return void
     */
    public function test_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('countries', [
                'id',
                'name',
                'official_name',
                'abbreviation',
                'capital',
                'iso_alpha_2',
                'iso_alpha_3',
                'iso_numeric',
                'calling_code',
                'tld',
                'emoji',
            ])
        );
    }

    /**
     *
     * @return void
     */
    public
    function test_model_can_be_instantiated()
    {
        $country = Country::factory()->create();

        $this->assertModelExists($country);
    }

    /**
     *
     * @return void
     */
    public
    function test_model_can_be_translated()
    {
        $en = $this->faker->country;
        $it = $this->faker->country;

        $country = Country::factory()->create([
            'name' => $en
        ]);

        $country->setTranslation('name', 'it', $it);

        app()->setLocale('en');
        $this->assertEquals($country->name, $en);

        app()->setLocale('it');
        $this->assertEquals($country->name, $it);
    }
}
