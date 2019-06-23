<?php


namespace App\Listener\Order;

use App\Cart\Cart;
use App\Cart\payments\Gateway;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;

class ProceessingPayment
{
    protected $gateway;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        try {

            $this->gateway->createCustomer()->charge($order->paymentMethod, $order->total()->amount());
            event(new OrderPaid($order));
        } catch (PaymentFailedException $exception) {
            event(new OrderPaymentFailed($order));
        }
    }
}