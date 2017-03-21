<?php

namespace App;

use TestCase;

class ProductFeedTest extends TestCase
{
    /** @test */
  public function it_provides_a_text_feed_of_products()
  {
      $products = factory(Product::class, 2)->create();
      $this->visit('/api/products/feed.txt')
         ->see($products->first()->description);
  }

  /** @test */
  public function it_provides_an_empty_text_feed_when_theres_no_products()
  {
      $this->visit('/api/products/feed.txt')
         ->see('description');
  }
}
