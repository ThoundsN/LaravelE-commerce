<?php

namespace Tests\Unit\Models\Products;

use App\Cart\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_uses_the_slug_for_the_route_key()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(),'slug');
    }

    public function test_it_has_many_categories(){
        $product = factory(Product::class)->create();

        $product->categories()->save(
            factory(Category::class)->create()
        );

        $this->assertInstanceOf(Category::class,$product->categories()->first());
    }

    public function test_it_has_many_variations(){
        $product = factory(Product::class)->create();

        $product->variations()->save(
            factory(ProductVariation::class)->create()
        );

        $this->assertInstanceOf(ProductVariation::class,$product->variations()->first());
    }

    public function test_it_returns_a_money_instance_of_the_price()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Money::class, $product->price);

    }

    public function test_it_returns_a_formatted_price()
    {
        $product = factory(Product::class)->create([
            'price' => 1000
        ]);

        $this->assertEquals('£10.00', $product->formattedPrice);

    }

    public function test_it_can_check_if_its_in_stock()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variations = factory(ProductVariation::class)->make()
        );

        $variations->stocks()->save(
            factory(Stock::class)->make()

        );

        $this->assertTrue($product->inStock());

    }


    public function test_it_can_get_the_stock_count()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variations = factory(ProductVariation::class)->make()
        );

        $variations->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => $q = 5
            ])

        );

        $this->assertEquals($product->stockCount(),$q);

    }


}


