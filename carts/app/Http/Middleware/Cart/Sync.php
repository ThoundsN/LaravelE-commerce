<?php

namespace App\Http\Middleware\Cart;

use App\Cart\Cart;
use Closure;

class Sync
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $cart;

    public function  __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function handle($request, Closure $next)
    {
        $this->cart->sync();

        if ($this->cart->hasChange()){
            return response()->json([
                'message' => 'Oh no, some items in your cart has changed, please review these changes before placing your orders'
            ],409);
        }



        return $next($request);
    }
}
