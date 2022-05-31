<?php

namespace Tests\Feature;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UnitControllerTest extends TestCase
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
        $user = User::factory()
            ->create(['admin' => false])
            ->first();

        Sanctum::actingAs($user);

        Unit::factory()->count(5)->create();

        $response = $this->getJson('/api/units');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data');

        $response = $this->getJson('/api/units?page[size]=2');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll('links', 'data', 'meta')
            );

        $response = $this->getJson('/api/units?include=unknown');
        $response
            ->assertStatus(400);

        $response = $this->getJson('/api/units?sort=unknown');
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $response = $this->getJson('/api/units?sort=-code');
        $response
            ->assertStatus(Response::HTTP_OK);
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

        $unit = Unit::factory()->create();

        $response = $this->getJson('/api/units/' . $unit->id);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => $unit->toArray()]);
    }


    /**
     * testing creation of a unit.
     *
     * @return void
     */
    public function test_store()
    {
        $response = $this->postJson('/api/units', []);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->postJson('/api/units', [
            'code' => 'kg',
        ]);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/units', [
            'code' => '',
        ]);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $payload = [
            'code' => 'kg',
        ];

        $response = $this->postJson('/api/units', $payload);
        $response
            ->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('units', $payload);
    }


    /**
     * test deleting a unit
     *
     * @return void
     */
    public function test_delete()
    {
        $unit = Unit::factory()->create();

        $response = $this->deleteJson('/api/units/' . $unit->id);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson('/api/units/' . $unit->id);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->deleteJson('/api/units/' . $unit->id);
        $response
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('units', ['id' => $unit->id]);
    }
}
