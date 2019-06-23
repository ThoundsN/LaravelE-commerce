<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('POST', 'api/orders')->assertStatus(401);
    }

    public function test_it_requires_address_id()
    {
        $user = factory(User::class)->create();

        $product =  $this->productWithStock();

        $user->cart()->sync($product);

        $response = $this->jsonAs($user, 'POST', 'api/orders'
        );

        $response->assertJsonValidationErrors('address_id');

    }


    public function test_it_requires_an_address_that_exists()
    {
        $user = factory(User::class)->create();

        $product =  $this->productWithStock();

        $user->cart()->sync($product);

        $response = $this->jsonAs($user, 'POST', 'api/orders',[
            'address_id'
            ]
        );

        $response->assertJsonValidationErrors('address_id');

    }

    public function test_it_requires_an_address_that_belongs_to_the_user()
    {
        $user = factory(User::class)->create();

        $product =  $this->productWithStock();

        $user->cart()->sync($product);

        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->jsonAs($user, 'POST', 'api/orders',[
                'address_id' => $address -> id
            ]
        );


        $response->assertJsonValidationErrors('address_id');

    }

    public function test_it_requires_shipping_method_id()
    {
        $user = factory(User::class)->create();

        $product =  $this->productWithStock();

        $user->cart()->sync($product);

        $response = $this->jsonAs($user, 'POST', 'api/orders'
        );

        $response->assertJsonValidationErrors('shipping_method_id');

    }


    public function test_it_requires_shipping_method_id_that_exists()
    {
        $user = factory(User::class)->create();

        $product =  $this->productWithStock();

        $user->cart()->sync($product);

        $response = $this->jsonAs($user, 'POST', 'api/orders',[
                'shipping_method_id'
            ]
        );

        $response->assertJsonValidationErrors('shipping_method_id');

    }

    public function test_it_requires_shipping_method_valid_for_the_given_address()
    {

        $user = factory(User::class)->create();

        $product =  $this->productWithStock();

        $user->cart()->sync($product);

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping = factory(ShippingMethod::class)->create();


        $response = $this->jsonAs($user, 'POST', 'api/orders',[
                'shipping_method_id' => $shipping->id,
                'address_id' => $address->id
            ]
        );

        $response->assertJsonValidationErrors('shipping_method_id');

    }

    public function test_it_can_create_an_order()
    {
        $user = factory(User::class)->create();

        $product =  $this->productWithStock();

        $user->cart()->sync($product);

        list($address, $shipping, $paymentmethod) = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders',[
                'shipping_method_id' => $shipping->id,
                'address_id' => $address->id,
                'payment_method_id'=> $paymentmethod->id
            ]
        );

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id,
            'payment_method_id'=> $paymentmethod->id
        ]);
    }

    public function test_it_attach_products_to_the_order()
    {
        $user = factory(User::class)->create();

        list($address, $shipping, $paymentmethod) = $this->orderDependencies($user);

        $product =  $this->productWithStock();

        $user->cart()->sync($product);

        $response = $this->jsonAs($user, 'POST', 'api/orders',[
                'shipping_method_id' => $shipping->id,
                'address_id' => $address->id,
                'payment_method_id'=> $paymentmethod->id
            ]
        );


        $this->assertDatabaseHas('product_variation_order', [
            'product_variation_id' => $product->id,
            'quantity' => 1
        ]);
    }

    protected function orderDependencies($user)
    {

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping = factory(ShippingMethod::class)->create();

        $paymentMethod = factory(PaymentMethod::class)->create([
            'user_id' => $user->id,
        ]);

        $shipping->countries()->attach($address->country);

        return array($address, $shipping, $paymentMethod);
    }

    protected function productWithStock()
    {
        $product = factory(ProductVariation::class)->create();

        $stock = factory(Stock::class)->create([
            'product_variation_id' => $product->id
        ]);

        return $product;
    }
}
