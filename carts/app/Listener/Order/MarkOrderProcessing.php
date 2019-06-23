<?php


namespace App\Listener\Order;


use App\Models\Order;

class MarkOrderProcessing
{
    public function handle($event)
    {
        $event->order->update([
            'status' => Order::PROCESSING
        ]);
    }
}