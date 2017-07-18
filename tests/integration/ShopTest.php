<?php

namespace App;

use TestCase;

class ShopTest extends TestCase
{
    use \UsesCart, \FlushesProductEvents;

    /** @test */
    public function it_shows_products_on_the_shop_page()
    {
        $productInStock = factory(Product::class)->create(['stock_qty' => 10]);
        $productOutOfStock = factory(Product::class)->create(['stock_qty' => 0]);
        $unlistedProduct = factory(Product::class)->create(['listed' => false]);

        $response = $this->get('/shop');
        $this->assertContains($productInStock->name, $response->getContent());
        $this->assertNotContains($productOutOfStock->name, $response->getContent());
        $this->assertNotContains($unlistedProduct->name, $response->getContent());
    }

    /** @test **/
    public function it_can_add_a_product_to_the_cart()
    {
        $product = factory(Product::class)->create(['stock_qty' => 10]);
        $product->makeUncategorised();

        $response = $this->get("/shop/uncategorised/{$product->slug}");
        $response = $this->post('/cart', [
          'product_id' => $product->id,
          'quantity' => 1,
        ]);

        $this->followRedirects($response)->assertSee("$product->name added to cart");

        $cart_row = \Cart::content()->first();
        $this->assertEquals($product->name, $cart_row->name);
        $this->assertEquals($product->getPrice()->asDecimal(), \Cart::total());
    }

    /** @test **/
    public function it_validates_the_quantity_when_adding_products_to_the_cart()
    {
        $product = factory(Product::class)->create(['stock_qty' => 10]);
        $product->makeUncategorised();
        $this->get($product->url);
        $response = $this->followRedirects($this->post('/cart',[
          'product_id' => $product->id,
          'quantity' => '',
        ]));

        $response->assertSee('quantity field is required');
    }

    /** @test **/
    public function it_cannot_add_a_quantity_of_products_greater_than_whats_in_stock()
    {
        $product = factory(Product::class)->create(['stock_qty' => 2]);
        $product->makeUncategorised();
        $this->get($product->url);

        $response = $this->followRedirects($this->post('/cart',[
          'product_id' => $product->id,
          'quantity' => 3,
        ]));
        $response->assertSee('You cannot add that amount to the cart');

        $this->assertEquals(0, \Cart::total());
    }

    /** @test **/
    public function it_cannot_add_more_than_available_products_including_whats_in_cart()
    {
        $product = factory(Product::class)->create([
          'stock_qty' => 2,
          ]);
        $product = factory(Product::class)->create(['stock_qty' => 2]);
        $product->makeUncategorised();
        $this->get($product->url);

        $response = $this->followRedirects($this->post('/cart',[
          'product_id' => $product->id,
          'quantity' => 1,
        ]));

        // there is already 1 of the product in the cart, we shouldn't be able
        // to add 2 more even though there is 2 in stock

        $response = $this->followRedirects($this->post('/cart',[
          'product_id' => $product->id,
          'quantity' => 2,
        ]));
        $response->assertSee('You cannot add that amount to the cart');
    }

    /** @test **/
    public function it_can_remove_an_item_from_the_cart()
    {
        $product = factory(Product::class)->create(['stock_qty' => 2]);
        $this->putProductInCart($product);
        $response = $this->get('/cart');

        $cartItem = \Cart::content()->first();

        $response->assertSee("/cart/{$cartItem->rowId}");

        $response = $this->followRedirects($this->delete("/cart/{$cartItem->rowId}"));

        $response->assertSee("{$product->name} removed from cart");
    }

    /** @test **/
    public function it_shows_a_list_of_products_in_a_given_category()
    {
        $product_group_1 = factory(Product::class, 4)->create();
        $product_group_2 = factory(Product::class, 3)->create();

        $product_category = factory('App\Term')->create([
          'taxonomy' => 'product_category',
          'term'     => 'Cats',
          'slug'     => 'cats',
          ]);

        $product_group_2->map(function ($product) use ($product_category) {
            $product->terms()->save($product_category);
        });

        $response = $this->get(route('products.index', $product_category->slug));
        $this->assertContains($product_group_2->first()->name, $response->getContent());
        $this->assertNotContains($product_group_1->first()->name, $response->getContent());
    }

    /** @test **/
    public function it_does_not_show_an_unlisted_product()
    {
        $product = factory(Product::class)->create(['listed' => false]);

        $response = $this->call('GET', $product->url);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /** @test **/
    public function it_shows_the_cart_page()
    {
        $product1 = factory(Product::class)->create(['stock_qty' => 2]);
        $product2 = factory(Product::class)->create(['stock_qty' => 2]);

        $this->putProductInCart($product1);
        $this->putProductInCart($product2);

        $response = $this->get('/cart');
        $response->assertSee($product1->first()->name);
        $response->assertSee(\Cart::total());
    }
}
