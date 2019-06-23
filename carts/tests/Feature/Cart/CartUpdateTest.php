<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
        $response = $this->json('PATCH', 'api/cart/1')->assertStatus(401);

    }

    public function test_it_fails_if_product_cannot_befound()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'PATCH', 'api/cart/1')->assertStatus(404);

    }

    public function test_it_requires_a_quantity()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $response = $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}")->assertJsonValidationErrors(['quantity']);

    }


    public function test_it_requires_a_numric_quantity()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $response = $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}",[
            'quantity' => 'one'
        ])->assertJsonValidationErrors(['quantity']);

    }

    public function test_it_update_the_quantity()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $user->cart()->attach($product, [
            'quantity' => 1
        ]);

        $response = $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => $q = 5
        ]);

        $this->assertDatabaseHas('cart_user' ,[
                'product_variation_id' => $product->id,
                'quantity' => $q,

            ]);

    }

}
