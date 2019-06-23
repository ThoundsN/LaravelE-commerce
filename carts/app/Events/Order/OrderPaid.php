<?php


namespace App\Events\Order;


use App\Models\Order;

class OrderPaid
{
    public $order;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}