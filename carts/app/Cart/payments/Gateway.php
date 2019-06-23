<?php
namespace App\Cart\payments;

interface Gateway
{
    public function withUser(\App\Models\User $user);

    public function createCustomer();

    public function user();
}