<?php

namespace Tests\Feature\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
        $response = $this->json('GET', 'api/cart')->assertStatus(401);
    }

    public function test_it_requires_products_to_be_array()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $product = factory(ProductVariation::class)->create()
        );

        $response = $this->jsonAs($user, 'GET', 'api/cart');


        $response->assertJsonFragment([
            'id' => $product->id,
        ]);

    }


    public function test_it_shows_if_the_cart_is_empty()
    {
        $user = factory(User::class)->create();


        $response = $this->jsonAs($user, 'GET', 'api/cart');


        $response->assertJsonFragment([
            'empty' => true,
        ]);

    }

    public function test_it_shows_a_formatted_subtotal()
    {
        $user = factory(User::class)->create();


        $response = $this->jsonAs($user, 'GET', 'api/cart');


        $response->assertJsonFragment([
            'subtotal' => '£0.00'
        ]);

    }

    public function test_it_shows_a_formatted_total()
    {
        $user = factory(User::class)->create();


        $response = $this->jsonAs($user, 'GET', 'api/cart');


        $response->assertJsonFragment([
            'total' => '£0.00'
        ]);

    }

    public function test_it_shows_changed_status()
    {
        $user = factory(User::class)->create();


        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create() ,[
                    'quantity' => 2
                ]
        );

        $response = $this->jsonAs($user, 'GET', 'api/cart')
        ->assertJsonFragment([
            'changed' =>true
        ]);

    }

    public function test_it_shows_a_formatted_total_with_shipping()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create([
            'price' => 1000
        ]);

        factory(Stock::class)->create([
            'product_variation_id' => $product->id
        ]);

        $cart->add([
            ['id' => $product->id, 'quantity' => 2]
        ]);


        $shippingmethod = factory(ShippingMethod::class)->create([
            'price' => 1000
        ]);

        $cart = $cart->withShipping($shippingmethod->id);


        $response = $this->jsonAs($user, 'GET', "api/cart?shipping_method_id={$shippingmethod->id}")
            ->assertJsonFragment([
                'total' => '£30.00'
            ]);

    }
}
