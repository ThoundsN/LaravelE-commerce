<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressShippingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_not_authenticated()
    {
        $this->json('GET', 'api/addresses/1/shipping')->assertStatus(401);
    }

    public function test_it_fails_if_the_address_cannot_be_found()
    {
        $user = factory(User::class)->create();


        $this->jsonAs($user, 'GET', 'api/addresses/1/shipping')->assertStatus(404);
    }


    public function test_it_failed_if_the_address_doesnot_belong_to_the_user()
    {
        $user = factory(User::class)->create();

        $address = factory(Address::class)->create();

        $id = $address->id;


        $response = $this->jsonAs($user, 'GET', "api/addresses/{$id}/shipping");


        $response->assertStatus(403);
    }

    public function test_it_shows_shipping_method_for_the_given_address()
    {
        $user = factory(User::class)->create();

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
            'country_id' => ($country = factory(Country::class)->create())->id
        ]);

        $country->shippingMethods()->save(
            $shipping = factory(ShippingMethod::class)->create()
        );

        $response = $this->jsonAs($user, 'GET', "api/addresses/{$address->id}/shipping");


        $response->assertJsonFragment([
            'id' => $shipping->id
        ]);
    }

}
