<?php

namespace Tests\Feature\products;

use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class productindexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_a_collection_of_Products()
    {
        $products = factory(Product::class,2)->create();

        $response = $this->json('GET','api/products');

        $products->each(function ($product) use ($response){
            $response->assertJsonFragment([
                'slug' => $product->slug
            ]);
        });
    }
}
