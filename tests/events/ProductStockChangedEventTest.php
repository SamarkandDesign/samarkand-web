<?php

namespace Events;

use App\Events\ProductStockChanged;
use App\Mail\ProductOutOfStock;
use App\Mail\ProductStockLow;
use App\Product;
use App\User;
use TestCase;

class ProductStockChangedEventTest extends TestCase
{
    use \FlushesProductEvents;

    /** @test **/
    public function it_alerts_to_a_product_being_out_of_stock()
    {
        \Mail::fake();
        $user = factory(User::class)->create(['is_shop_manager' => true]);
        $product = factory(Product::class)->create(['stock_qty' => 0]);

        event(new ProductStockChanged($product));

        \Mail::assertSent(ProductOutOfStock::class);
    }

    /** @test **/
    public function it_alerts_to_a_low_stock_product()
    {
        \Mail::fake();
        $user = factory(User::class)->create(['is_shop_manager' => true]);
        $product = factory(Product::class)->create(['stock_qty' => 1]);

        event(new ProductStockChanged($product));

        \Mail::assertSent(ProductStockLow::class);
    }
}
