<?php

namespace Tests\Browser;

use App\Mail\OrderConfirmed;
use App\Mail\ProductOutOfStock;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PurchaseTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCanPurchaseAProduct()
    {
        $this->browse(function (Browser $b) {
            \Mail::fake();

            $product = factory('App\Product')->create(['stock_qty' => 1]);
            $shippingMethod = factory('App\ShippingMethod')->create(['description' => 'UK Shipping']);
            $shippingMethod->allowCountries(['gb']);

            $b->visit('/shop');
            $b->assertSee(strtoupper($product->name));
            $b->clickLink($product->name);
            $b->press('Add To Cart');
            $b->assertSee("{$product->name} added to cart");
            $b->clickLink('Checkout');
            $b->assertSee('ORDER SUMMARY');
            $b->type('email', 'foo@example.com');

            $b->type('#billing_address_name', 'Foo Face');
            $b->type('#billing_address_line_1', 'Bar House');
            $b->type('#billing_address_city', 'Some City');
            $b->type('#billing_address_postcode', 'PC1 1AA');
            $b->press('Continue');

            $b->assertSee('PAY');
            $b->assertSee('UK Shipping');

            $b->waitFor('#cc-number');

            $b->type('#cc-number', '4242424242424242');
            $b->type('#cc-exp', '10/25');
            $b->type('#cc-cvc', '123');
            $b->press('Place Order');

            $b->waitForText('ORDER COMPLETED');

            Mail::assertSent(OrderConfirmed::class);
            Mail::assertSent(ProductOutOfStock::class);
        });
    }
}
