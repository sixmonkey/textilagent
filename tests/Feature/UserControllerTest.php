<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * testing the index.
     *
     * @return void
     */
    public function test_index()
    {
        $users = User::factory()->count(100)->create();

        Sanctum::actingAs($users->first());

        $response = $this->getJson('/api/users');
        $response
            ->assertStatus(200)
            ->assertJsonCount(100, 'data');

        $response = $this->getJson('/api/users?page[size]=2');
        $response
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll('links', 'data', 'meta')
            );

        $response = $this->getJson('/api/users?include=unknown');
        $response
            ->assertStatus(400);

        $response = $this->getJson('/api/users?include=orders');
        $response
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('data')
                    ->has(
                        'data.0',
                        fn ($json) => $json->where('id', 1)
                            ->has('orders')
                            ->etc()
                    )
            );

        $response = $this->getJson('/api/users?sort=unknown');
        $response
            ->assertStatus(400);

        $response = $this->getJson('/api/users?sort=-name');
        $response
            ->assertStatus(200);
    }
}
