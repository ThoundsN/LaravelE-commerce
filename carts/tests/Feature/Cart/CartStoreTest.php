<?php

namespace Tests\Feature\Cart;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
        $response = $this->json('POST', 'api/cart')->assertStatus(401);
    }

    public function test_it_requires_products_to_be_array()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => 1
        ]);

        $response->assertJsonValidationErrors('products');

    }


    public function test_it_requires_products_to_exist()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user,'POST', 'api/cart',[
            'products' => [
                ['id'=>1, 'quantity' => 1]
            ]
        ])->assertJsonValidationErrors('products.0.id');

    }


    public function test_it_requires_products_quantity_to_be_numeirc()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user,'POST', 'api/cart',[
            'products' => [
                ['id'=>1, 'quantity' => 'one']
            ]
        ])->assertJsonValidationErrors('products.0.quantity');

    }

    public function test_it_requires_products_quantity_to_be_atleastone()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user,'POST', 'api/cart',[
            'products' => [
                ['id'=>1, 'quantity' => 0]
            ]
        ])->assertJsonValidationErrors('products.0.quantity');

    }

    public function test_it_can_add_products_to_the_user_cart()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $response = $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => $product->id, 'quantity' => 1]
            ]
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => 1
        ]);

    }

}
