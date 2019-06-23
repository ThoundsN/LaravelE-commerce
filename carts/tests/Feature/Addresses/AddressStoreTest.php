<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_not_authenticated()
    {
        $this->json('POST', 'api/addresses')->assertStatus(401);
    }

    public function test_it_requires_post_data()
    {
        $user = factory(User::class)->create();


        $this->jsonAs($user, 'POST', 'api/addresses')->assertJsonValidationErrors([
            'name'
        ]);
    }

    public function test_it_requires_a_valid_country_id()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/addresses', [
            'country_id' => 1
        ])->assertJsonValidationErrors([
            'country_id'
        ]);
    }

    public function test_it_stores_an_address()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/addresses', $payload = [
            'country_id' => factory(Country::class)->create()->id,
            'name' => 'dsad',
            'address_1' => 'sada',
            'city' => 'asdsa',
            'postal_code' => '3213',
        ]);

        $this->assertDatabaseHas('addresses', array_merge($payload,
            [
                'user_id' => $user->id
            ]
        ));
    }


    public function test_it_returns_an_address_when_created()
    {
        $user = factory(User::class)->create();

         $response =$this->jsonAs($user, 'POST', 'api/addresses', $payload = [
            'country_id' => factory(Country::class)->create()->id,
            'name' => 'dsad',
            'address_1' => 'sada',
            'city' => 'asdsa',
            'postal_code' => '3213',
        ]);

        $response->assertJsonFragment([
            'id' => json_decode($response->getContent())->data->id,

        ]);

    }

}
