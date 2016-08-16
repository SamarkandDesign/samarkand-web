<?php

use App\Search\TokenGenerator;

class TokenGeneratorTest extends TestCase {
  
  /** @test */
  public function it_generates_a_token() {
    SearchIndex::shouldReceive('getClient')->once()->andReturn(
      Mockery::mock([
        'generateSecuredApiKey' => 'abc123'
        ])
      );

    $generator = App::make(TokenGenerator::class);

    $this->assertNotEmpty($generator->getToken());
  }
}
