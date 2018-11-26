<?php

namespace Integration;

use TestCase;

class HealthcheckTest extends TestCase
{
  /** @test **/
  public function it_responds_with_ok_if_the_app_is_working()
  {
    $response = $this->call('GET', '/healthz');
    $this->assertEquals(200, $response->status());
  }
}
