<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariationStockView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::raw("
CREATE VIEW product_variation_stock_view AS
SELECT 
 ANY_VALUE(product_variations.product_id) AS product_id,
 ANY_VALUE(product_variations.id) AS product_variation_id, COALESCE(SUM(stocks.quantity)- COALESCE(SUM(product_variation_order.quantity),0),0) AS stock, CASE WHEN COALESCE(SUM(stocks.quantity)- COALESCE(SUM(product_variation_order.quantity),0),0) >0 THEN TRUE ELSE FALSE END in_stock
FROM product_variations
LEFT JOIN(
SELECT 	
						stocks.product_variation_id AS id, SUM(stocks.quantity) AS quantity
FROM stocks
GROUP BY stocks.product_variation_id
					) AS stocks USING (id)
LEFT JOIN(
SELECT 
						product_variation_order.product_variation_id AS id, SUM(product_variation_order.quantity) AS quantity
FROM product_variation_order
GROUP BY product_variation_order.product_variation_id
			 	) AS product_variation_order USING (id)
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
             drop view if exists product_variation_stock_view
        ');
    }
}
