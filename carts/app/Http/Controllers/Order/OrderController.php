<?php

namespace App\Http\Controllers\Order;

use App\Cart\Cart;
use App\Events\Order\OrderCreated;
use App\Http\Requests\Orders\OrderStoreReqeust;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->middleware(['cart.sync','cart.isnotempty'])->only('store');
    }

    public function index (Request $request){
        $orders = $request->user()->orders()->with(['products','products.type',
            'products.stock','products.product',
            'products.product.variations',
            'products.product.variations.stock',
            'address', 'shippingMethod'])
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    public function store(OrderStoreReqeust $request,Cart $cart)
    {

        $order =  $this->createOrder($request,$cart);


        $order->products()->sync($cart->products()->forSyncing());



        event(new OrderCreated($order));

        return new OrderResource($order);
    }

    public function createOrder(Request $request,Cart $cart)
    {
        return  $request->user()->orders()->create(array_merge($request->only(['address_id', 'shipping_method_id'
        , 'payment_method_id'
        ]),[
            'subtotal' => $cart->subTotal()->amount()
        ]));
    }


}
