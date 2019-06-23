<?php

namespace Tests\Feature\Models\PaymentMethod;

use App\Models\PaymentMethod;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_sets_old_payment_to_not_default_when_creating()
    {
        $user = factory(User::class)->create();

        $oldpayment = factory(PaymentMethod::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        $newpayment = factory(PaymentMethod::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);


        $this->assertEquals($oldpayment->fresh()->default,0);
    }
}
