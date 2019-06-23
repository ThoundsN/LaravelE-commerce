<?php

namespace App\Http\Controllers\Products;

use App\Http\Resources\ProductsIndexResource;
use App\Http\Resources\ProductResource;

use App\Models\Product;
use App\Scoping\Scopes\CategoryScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function index(){

        $products = Product::with(['variations.stock'])->withScopes($this->scopes())->paginate(10);

        return ProductsIndexResource::collection($products);
    }

    public function show(Product $product){

        $product->load(['variations.product', 'variations.stock','variations.type']);

        return new ProductResource(
            $product
        );

    }

    protected function scopes(){
        return [
            'category' => new CategoryScope(),
        ];
    }
}
