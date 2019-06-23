<?php

namespace Tests\Unit\Models\Order;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_many_products()
    {
        $order = factory(Order::class)->create();

        $product = $order->products()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $this->assertInstanceOf(ProductVariation::class, $order->products->first());
    }


    public function test_it_has_a_quantity_attached()
    {
        $order = factory(Order::class)->create();

        $product = $order->products()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]
        );

        $this->assertEquals(2, $order->products->first()->pivot->quantity);
    }


    public function test_it_has_belongs_to_a_payment_method()
    {
        $payment = factory(PaymentMethod::class)->create();

        $order = factory(Order::class)->create([
            'payment_method_id' => $payment->id
        ]);

//        dd($order->paymentMethod);

        $this->assertInstanceOf(PaymentMethod::class, $order->paymentMethod->first());
    }
}
