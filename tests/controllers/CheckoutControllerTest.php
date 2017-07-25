<?php

namespace App\Http\Controllers;

class CheckoutControllerTest extends \TestCase
{
    use \UsesCart, \FlushesProductEvents;

    /** @test **/
    public function it_shows_the_checkout_page()
    {
        \Event::fake();

        $product = $this->putProductInCart();

        $response = $this->get('/checkout');

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }
}
