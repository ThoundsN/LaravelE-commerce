<?php


namespace App\Cart\payments;


use App\Models\PaymentMethod;

interface GatewayCustomer
{
    public function charge(PaymentMethod $card, $amount);
    public function addCard($token);
}