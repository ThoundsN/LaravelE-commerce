<?php

namespace App\Http\Controllers\PaymentMethods;

use App\Http\Requests\PaymentMethodStoreRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;

use App\Cart\payments\Gateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PaymentMethodController extends Controller
{
    protected $gateway;

    public function __construct(Gateway $gateway)
    {
        $this->middleware(['auth:api']);
        $this->gateway = $gateway;
    }

    public function index(Request $request)
    {
        return PaymentMethodResource::collection(
            $request->user()->paymentMethods()->get()
        );
    }

    public function store(PaymentMethodStoreRequest $request)
    {


        $card = $this->gateway->withUser($request->user())->createCustomer()
            ->addCard($request->token);

        return new PaymentMethodResource($card);
    }
}
