<?php

namespace Tests\Feature\products;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductShowTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_a_product_cant_be_found()
    {
        $this->json('GET','api/products/nope')
            ->assertStatus(404);

    }
}
