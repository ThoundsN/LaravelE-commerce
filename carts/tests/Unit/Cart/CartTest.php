<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Cart\Money;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_can_add_products_to_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create();

        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $this->assertCount(1, $user->fresh()->cart);
    }

    public function test_it_can_increment_products_to_the_cart()
    {

        $product = factory(ProductVariation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );


        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $cart = new Cart(
            $user
        );


        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $this->assertEquals(2, $user->fresh()->cart->first()->pivot->quantity);
    }



    public function test_it_can_update_quantities_in_the_cart()
    {

        $product = factory(ProductVariation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $cart->update($product->id, 2);

        $this->assertEquals(2, $user->fresh()->cart->first()->pivot->quantity);
    }

    public function test_it_can_delete_a_product_from_the_cart()
    {

        $product = factory(ProductVariation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $cart->delete($product->id);

        $this->assertCount(0, $user->fresh()->cart);
    }


    public function test_it_can_empty_all_product_from_the_cart()
    {

        $product = factory(ProductVariation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $cart->empty();

        $this->assertCount(0, $user->fresh()->cart);
    }

    public function test_it_can_check_whether_the_cart_is_empty_of_quantities()
    {

        $product = factory(ProductVariation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->add([
            ['id' => $product->id, 'quantity' => 0]
        ]);


        $this->assertTrue($cart->isEmpty());
    }


    public function test_it_returns_a_money_instance_for_the_subtotal()
    {

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $this->assertInstanceOf(Money::class,$cart->subTotal());
    }

    public function test_it_can_gets_the_correct_subtotal()
    {

        $product = factory(ProductVariation::class)->create([
            'price' => 1000
        ]);

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->add([
            ['id' => $product->id, 'quantity' => 2]
        ]);


        $this->assertEquals($cart->subTotal()->amount(), 2000);
    }

    public function test_it_returns_a_money_instance_for_the_total()
    {

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $this->assertInstanceOf(Money::class,$cart->total());
    }

    public function test_it_sync_the_cart_to_updated_quantity()
    {

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create();
        $anotherproduct = factory(ProductVariation::class)->create();

        $user->cart()->attach(
           [ $product->id => [
                    'quantity' => 2
                ],
               $anotherproduct->id =>[
                   'quantity' => 2

               ]
           ]
        );

        $cart->sync();

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity,0);
        $this->assertEquals($user->fresh()->cart->get(1)->pivot->quantity,0);
        $this->assertTrue($cart->hasChange());
    }


    public function test_it_returns_the_correct_total_with_shipping()
    {

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create([
            'price' => 1000
        ]);

        $cart->add([
            ['id' => $product->id, 'quantity' => 2]
        ]);

        $shippingmethod = factory(ShippingMethod::class)->create([
            'price' => 1000
        ]);

        $cart = $cart->withShipping($shippingmethod->id);


        $this->assertEquals($cart->total()->amount(),3000);
    }
}