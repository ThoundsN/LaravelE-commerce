<?php


namespace App\Cart;


use App\Http\Requests\Cart\CartStoreRequest;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;

class Cart
{
    protected $user;
    protected $changed = false;
    protected  $shipping ;

    public function __construct($user)
    {
        $this->user = $user;

    }

    public function add($products)
    {
        $products = $this->getStorePayload($products);

        $this->user->cart()->syncWithoutDetaching($products);
    }

    public function update($productId, $quantity)
    {

        $this->user->cart()->updateExistingPivot($productId, [
            'quantity' => $quantity,

        ]);
    }

    public function withShipping($shippingId)
    {
        $this->shipping = ShippingMethod::find($shippingId);
        return $this;
    }

    public function products()
    {
        return $this->user->cart;
    }

    public function delete($productId)
    {
        $this->user->cart()->detach($productId);
    }


    public function empty()
    {
        $this->user->cart()->detach();
    }

    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') <= 0;
    }

    public function sync()
    {
        $this->user->cart->each(function ($product) {
            $quantity = $product->minStock($product->pivot->quantity);

            if ($quantity != $product->pivot->quantity) $this->changed = true;

            $product->pivot->update([
                'quantity' => $quantity
            ]);
        });
    }

    public function hasChange()
    {
        return $this->changed;
    }

    public function subTotal()
    {
        $subTotal = $this->user->cart->sum(function ($product) {
            return $product->price->amount() * $product->pivot->quantity;
        });

        return new Money($subTotal);
    }

    public function total()
    {
        if ($this->shipping) {
            return $this->subTotal()->add($this->shipping->price);
        }

        return $this->subTotal();
    }

    protected function getStorePayload($products)
    {
        return  collect($products)->keyBy('id')->map(function ($product) {
            return [
                'quantity' => $product['quantity']+$this->getCurrentQuantity($product['id'])
            ];
        })->toArray();

    }

    protected function getCurrentQuantity($productId)
    {
        if($product = $this->user->cart()->where('id',$productId)->first()){
            return $product->pivot->quantity;
        }
        return 0;
    }


}