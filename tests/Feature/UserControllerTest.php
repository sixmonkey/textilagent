<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
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
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(100, 'data');

        $response = $this->getJson('/api/users?page[size]=2');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll('links', 'data', 'meta')
            );

        $response = $this->getJson('/api/users?include=unknown');
        $response
            ->assertStatus(400);

        $response = $this->getJson('/api/users?include=orders');
        $response
            ->assertStatus(Response::HTTP_OK)
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
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $response = $this->getJson('/api/users?sort=-name');
        $response
            ->assertStatus(Response::HTTP_OK);

        $admin = $users->first();
        $admin->admin = false;
        $admin->save();

        $response = $this->getJson('/api/users');
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);
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
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => $user->toArray()]);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/users/' . $user->id);
        $response
            ->assertStatus(Response::HTTP_OK);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->getJson('/api/users/' . $user->id);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * testing creation of a user.
     *
     * @return void
     */
    public function test_store()
    {
        $response = $this->postJson('/api/users', []);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->postJson('/api/users', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ]);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/users', [
            'name' => $this->faker->name,
        ]);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];

        $response = $this->postJson('/api/users', $payload);
        $response
            ->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('users', $payload);
    }

    public function test_update()
    {
        $user = User::factory()->create();

        $response = $this->patchJson('/api/users/' . $user->id, []);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->patchJson('/api/users/' . $user->id, [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ]);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);

        Sanctum::actingAs($user);

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];
        $response = $this->patchJson('/api/users/' . $user->id, $payload);
        $response
            ->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', $payload);

        Sanctum::actingAs($user);

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];
        $response = $this->patchJson('/api/users/' . $user->id, $payload);
        $response
            ->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', $payload);



        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];
        $response = $this->patchJson('/api/users/' . $user->id, $payload);
        $response
            ->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', $payload);
    }
}
