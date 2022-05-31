<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompaniesControllerTest extends TestCase
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
            ->create(['admin' => true]);

        Sanctum::actingAs($user);

        $companies = Company::factory()->count(100)->create();

        $response = $this->getJson('/api/companies');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(100, 'data');

        $response = $this->getJson('/api/companies?page[size]=2');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll('links', 'data', 'meta')
            );

        $response = $this->getJson('/api/companies?include=unknown');
        $response
            ->assertStatus(400);

        $response = $this->getJson('/api/companies?include=purchases');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('data')
                    ->has(
                        'data.0',
                        fn ($json) => $json->where('id', 1)
                            ->has('purchases')
                            ->etc()
                    )
            );

        $response = $this->getJson('/api/companies?sort=unknown');
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $response = $this->getJson('/api/companies?sort=-name');
        $response
            ->assertStatus(Response::HTTP_OK);

        $user->admin = false;
        $user->save();

        $response = $this->getJson('/api/companies');
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
            ->create(['admin' => true]);

        Sanctum::actingAs($user);

        $company = Company::factory()->create();

        $response = $this->getJson('/api/companies/' . $company->id);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => $company->toArray()]);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->getJson('/api/companies/' . $company->id);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * testing creation of a company.
     *
     * @return void
     */
    public function test_store()
    {
        $response = $this->postJson('/api/companies', []);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->postJson('/api/companies', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ]);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/companies', [
            'name' => '',
        ]);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];

        $response = $this->postJson('/api/companies', $payload);
        $response
            ->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('companies', $payload);
    }

    /**
     * test updating a company
     *
     * @return void
     */
    public function test_update()
    {
        $company = Company::factory()->create();

        $response = $this->patchJson('/api/companies/' . $company->id, []);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->patchJson('/api/companies/' . $company->id, [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ]);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);


        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];
        $response = $this->patchJson('/api/companies/' . $company->id, $payload);
        $response
            ->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('companies', $payload);
    }

    /**
     * test deleting a company
     *
     * @return void
     */
    public function test_delete()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson('/api/companies/' . $company->id);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $otherUser = User::factory()
            ->create(['admin' => false]);

        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson('/api/companies/' . $company->id);
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()
            ->create(['admin' => true]);

        Sanctum::actingAs($admin);

        $response = $this->deleteJson('/api/companies/' . $company->id);
        $response
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
