<?php

use AlgoliaSearch\Client;
use App\Search\TokenGenerator;

class TokenGeneratorTest extends TestCase
{
    /** @test */
  public function it_generates_a_token()
  {
      // override the real algolia client
    App::singleton(Client::class, function () {
        return Mockery::mock(Client::class, [
      'generateSecuredApiKey' => 'abc123',
      ]);
    });

      $generator = App::make(TokenGenerator::class);

      $this->assertEquals('abc123', $generator->getProductSearchToken());
  }
}
