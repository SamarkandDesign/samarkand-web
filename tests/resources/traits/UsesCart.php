<?php

use App\Product;

trait UsesCart
{
    protected function putProductInCart($product = null)
    {
        $product = $product ? $product : factory(Product::class)->create(['stock_qty' => 10]);
        $product->makeUncategorised();

        $this->post('/cart', [
          'product_id' => $product->id,
          'quantity' => 1,
        ]);

        return $product;
    }
}
