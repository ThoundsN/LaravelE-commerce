<?php

namespace App\Http\Resources\Cart;

use App\Cart\Money;
use App\Http\Resources\ProductVariationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductVariationResource extends ProductVariationResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return array_merge(parent::toArray($request),[
            'product' => $this->product,
            'quantity' => $this->pivot->quantity,
            'total' => $this->getTotal()->formatted()
        ]);
    }

    public function getTotal()
    {
        return new Money($this->pivot->quantity * $this->price->amount());

    }
}
