<?php

namespace Tests\Unit\Models\ShippingMethods;

use App\Cart\Money;
use App\Models\Country;
use App\Models\ShippingMethod;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingMethodTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_returns_a_Money_instance()
    {
        $shippingmethod = factory(ShippingMethod::class)->create();

        $this->assertInstanceOf(Money::class, $shippingmethod->price);
    }

    public function test_it_returns_a_formated_price()
    {
        $shippingmethod = factory(ShippingMethod::class)->create([
            'price' => 0
        ]);

        $this->assertEquals($shippingmethod->formattedPrice,  '£0.00');
    }

    public function test_it_belongs_to_countries()
    {
        $shippingmethod = factory(ShippingMethod::class)->create();

        $shippingmethod->countries()->attach(
            factory(Country::class)->create()
        );

        $this->assertInstanceOf(Country::class, $shippingmethod->countries->first());
    }
}
