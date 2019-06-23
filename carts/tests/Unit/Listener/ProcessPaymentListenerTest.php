<?php

namespace Tests\Unit\Listener;

use App\Cart\payments\Gateways\StripeGateway;
use App\Cart\payments\Gateways\StripeGatewayCustomer;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;
use App\Listener\Order\ProceessingPayment;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessPaymentListenerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_charges_the_chosen_payment_the_correct_amount()
    {
        Event::fake();

        list($user, $payment, $event, $order) = $this->createEvent();

        $gateway = \Mockery::mock(StripeGateway::class);

        $gateway->shouldReceive('withUser')->andReturn($gateway)
            ->shouldReceive('createCustomer')->andReturn(
                $customer = \Mockery::mock(StripeGatewayCustomer::class)
            );

        $customer->shouldReceive('charge')->with(
            $order->paymentMethod, $order->total()->amount()
        );

        $listener = new ProceessingPayment($gateway);

        $listener->handle($event);

        Event::assertDispatched(OrderPaid::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
        });

    }

    public function test_it_fires_the_order_failed_event()
    {
        Event::fake();

        list($user, $payment, $event, $order) = $this->createEvent();

        $gateway = \Mockery::mock(StripeGateway::class);

        $gateway->shouldReceive('withUser')->andReturn($gateway)
            ->shouldReceive('createCustomer')->andReturn(
                $customer = \Mockery::mock(StripeGatewayCustomer::class)
            );

        $customer->shouldReceive('charge')->with(
            $order->paymentMethod, $order->total()->amount()
        )->andThrow(PaymentFailedException::class)
        ;

        $listener = new ProceessingPayment($gateway);

        $listener->handle($event);

        Event::assertDispatched(OrderPaymentFailed::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
        });

    }

    public function createEvent()
    {
        $user = factory(User::class)->create();

        $payment = factory(PaymentMethod::class)->create([
            'user_id' => $user->id
        ]);

        $event = new OrderCreated(
            $order = factory(Order::class)->create([
                'user_id' => $user->id,
                'payment_method_id' => $payment->id,
            ])
        );

        return [$user, $payment, $event, $order];
    }
}
