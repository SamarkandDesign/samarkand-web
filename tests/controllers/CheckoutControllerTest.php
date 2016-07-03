<?php

namespace App\Http\Controllers;

class CheckoutControllerTest extends \TestCase
{
    use \UsesCart;

    /** @test **/
    public function it_shows_the_checkout_page()
    {
        $this->withoutEvents();
        $product = $this->putProductInCart();

        $this->visit('checkout')
             ->see($product->name);
    }
}
