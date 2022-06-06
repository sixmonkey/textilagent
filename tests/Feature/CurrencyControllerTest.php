<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CurrencyControllerTest extends TestCase
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

        Currency::factory()->count(5)->create();

        $response = $this->getJson('/api/currencies');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data');

        $response = $this->getJson('/api/currencies?page[size]=2');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll('links', 'data', 'meta')
            );

        $response = $this->getJson('/api/currencies?include=unknown');
        $response
            ->assertStatus(400);

        $response = $this->getJson('/api/currencies?sort=unknown');
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $response = $this->getJson('/api/currencies?sort=-code');
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

        $currency = Currency::factory()->create();

        $response = $this->getJson('/api/currencies/' . $currency->id);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => $currency->toArray()]);
    }


    /**
     * testing creation of a currency.
     *
     * @return void
     */
    public function test_store()
    {
        $response = $this->postJson('/api/currencies', []);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->postJson('/api/currencies', [
            'code' => $this->faker->currencyCode,
        ]);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/currencies', [
            'code' => '',
        ]);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $payload = [
            'code' => $this->faker->currencyCode,
        ];

        $response = $this->postJson('/api/currencies', $payload);
        $response
            ->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('currencies', $payload);

        $response = $this->postJson('/api/currencies', $payload);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     * test deleting a currency
     *
     * @return void
     */
    public function test_delete()
    {
        $currency = Currency::factory()->create();

        $response = $this->deleteJson('/api/currencies/' . $currency->id);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson('/api/currencies/' . $currency->id);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->deleteJson('/api/currencies/' . $currency->id);
        $response
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('currencies', ['id' => $currency->id]);
    }
}
