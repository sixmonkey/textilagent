<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $admin = $users->first();
        $admin->admin = true;
        $admin->save();


        Sanctum::actingAs($admin);

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

        $admin = $users->first();
        $admin->admin = false;
        $admin->save();

        $response = $this->getJson('/api/users');
        $response
            ->assertStatus(403);
    }

    /**
     * testing the index.
     *
     * @return void
     */
    public function test_show()
    {
        $user = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/users/' . $user->id);
        $response
            ->assertStatus(200)
            ->assertJson(['data' => $user->toArray()]);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/users/' . $user->id);
        $response
            ->assertStatus(200);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->getJson('/api/users/' . $user->id);
        $response
            ->assertStatus(403);
    }
}
