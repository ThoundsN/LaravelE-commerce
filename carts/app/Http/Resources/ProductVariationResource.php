<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Collection){
            return ProductVariationResource::collection($this->resource);
        }
        return [
            'id' =>$this->id,
            'name'=> $this->name,
            'price' => $this->formattedPrice,
            'price_varies' => $this->priceVaries(),
            'type' => $this->type->name,
            'in_stock' => $this->inStock(),
            'stock_count' => $this->stockCount(),
            'product' => new ProductsIndexResource($this->product),

        ];
    }
}
