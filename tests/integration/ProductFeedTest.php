<?php

namespace App;

use TestCase;

class ProductFeedTest extends TestCase
{
    /** @test */
  public function it_provides_a_text_feed_of_products()
  {
      $listedProducts = factory(Product::class, 2)->create(['listed' => true]);
      $unlistedProducts = factory(Product::class, 2)->create(['listed' => false]);
      $this->visit('/api/products/feed.txt')
         ->see($listedProducts->first()->description)
         ->dontSee($unlistedProducts->first()->description);
  }

  /** @test */
  public function it_provides_an_empty_text_feed_when_theres_no_products()
  {
      $this->visit('/api/products/feed.txt')
         ->see('description');
  }
}
