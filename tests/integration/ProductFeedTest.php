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
      $response = $this->get('/api/products/feed.txt');

      $this->assertContains($listedProducts->first()->description, $response->getContent());
      $this->assertNotContains($unlistedProducts->first()->description, $response->getContent());
  }

  /** @test */
  public function it_provides_an_empty_text_feed_when_theres_no_products()
  {
      $response = $this->get('/api/products/feed.txt');

      $this->assertContains('description', $response->getContent());
  }
}
