<?php

namespace App\Http\Controllers;

use App\Product;

class ProductsControllerTest extends \TestCase
{
  use \FlushesProductEvents;

  /** @test **/
  public function it_can_view_the_shop_page()
  {
    $products = factory(Product::class, 4)->create();

    $response = $this->get('/shop');
    $this->assertContains($products->first()->name, $response->getContent());
  }

  /** @test **/
  public function it_can_view_a_single_product()
  {
    $product = factory(Product::class)->create();

    $response = $this->get("/shop/{$product->product_category->slug}/{$product->slug}");

    $content = $response->getContent();
    $this->assertContains($product->name, $content);
    $this->assertContains($product->description, $content);
  }
}
