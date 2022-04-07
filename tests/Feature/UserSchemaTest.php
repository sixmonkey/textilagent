<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Schema;
use Tests\TestCase;

class UserSchemaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id',
                'name',
                'email',
                'email_verified_at',
                'password',
                'remember_token',
                'admin',
                'phone',
                'address',
                'country_id',
            ]),
            1
        );
    }

    /**
     *
     * @return void
     */
    public function test_model_can_be_instantiated()
    {
        $user = User::factory()->create();

        $this->assertModelExists($user);
    }

    /**
     * @return void
     */
    public function test_user_belongs_to_country()
    {
        $country = Country::factory()->create();
        $user = User::factory()
            ->for($country)
            ->create();

        $this->assertInstanceOf(Country::class, $user->country);
    }

    /**
     * @return void
     */
    public function test_user_can_get_country_name()
    {
        $country = Country::factory()->create();
        $user = User::factory()
            ->for($country)
            ->create();

        $this->assertEquals($country->name, $user->country->name);
    }
}
