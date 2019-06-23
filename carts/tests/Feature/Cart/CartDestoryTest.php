<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartDestoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
        $response = $this->json('DELETE', 'api/cart/1')->assertStatus(401);

    }

    public function test_it_fails_if_product_cannot_befound()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'DELETE', 'api/cart/1')->assertStatus(404);

    }

    public function test_it_delete_the_product()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $user->cart()->attach($product, [
            'quantity' => 1
        ]);

        $response = $this->jsonAs($user, 'DELETE', "api/cart/{$product->id}");

        $this->assertDatabaseMissing('cart_user' ,[
            'product_variation_id' => $product->id

        ]);

    }
}
