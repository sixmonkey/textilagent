<?php

namespace Tests\Feature;

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
            ]), 1);
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
}
