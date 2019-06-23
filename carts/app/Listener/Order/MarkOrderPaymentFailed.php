<?php


namespace App\Listener\Order;


use App\Models\Order;

class MarkOrderPaymentFailed
{
    public function handle($event)
    {
        $event->order->update([
            'status' => Order::PAYMENT_FAILED
        ]);
    }
}