<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\PaymentMethod::class, function (Faker $faker) {
    return [
        'card_type' => 'visa',
        'last_four' => '4324',
        'provider_id' => str_random(10),
        'user_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
