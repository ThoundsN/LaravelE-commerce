<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\ShippingMethod::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'price'=>  100
    ];
});
