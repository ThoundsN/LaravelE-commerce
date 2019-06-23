<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Order::class, function (Faker $faker) {
    return [
        'address_id' => factory(\App\Models\Address::class)->create()->id,
        'shipping_method_id' => factory(\App\Models\ShippingMethod::class)->create()->id,
        'user_id' => factory(\App\Models\User::class)->create()->id,
        'subtotal' =>  1000,
        'payment_method_id' => factory(\App\Models\PaymentMethod::class)->create()->id
    ];
});
