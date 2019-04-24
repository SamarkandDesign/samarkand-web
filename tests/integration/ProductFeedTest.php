<?php

namespace App;

use TestCase;

class ProductFeedTest extends TestCase
{


  /** @test */
  public function it_provides_an_empty_text_feed_when_theres_no_products()
  {
    $response = $this->get('/api/products/feed.txt');

    $response->assertStatus(410);
  }
}
