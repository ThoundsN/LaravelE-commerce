<?php

namespace Tests\Unit\Listener;

use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPaymentFailed;
use App\Listener\Order\MarkOrderPaymentFailed;
use App\Listener\Order\MarkOrderProcessing;
use App\Listener\Order\ProceessingPayment;
use App\Models\Order;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarkOrderProcessingListenerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_marks_order_as_processing()
    {
        $event = new OrderPaid(
            $order = factory(Order::class)->create([
                'user_id' => factory(User::class)->create()->id
            ])
        );

        $listener = new MarkOrderProcessing();

        $listener->handle($event);

        $this->assertEquals($order->fresh()->status, Order::PROCESSING);
    }
}
