<?php


namespace Tests\Unit\Listener;


use App\Events\Order\OrderPaid;
use App\Listener\Order\CreateTransaction;
use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class CreateTransactionListenerTest extends TestCase
{
    public function test_it_creates_the_transaction()
    {
        $event = new OrderPaid(
            $order = factory(Order::class)->create([
                'user_id' => factory(User::class)->create()->id
            ])
        );

        $listener = new CreateTransaction();

        $listener->handle($event);

        $this->assertDatabaseHas('transaction', [
            'order_id' => $order->id,
            'total' => $order->total()->amount()
        ]);
    }
}