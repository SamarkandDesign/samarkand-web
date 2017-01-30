<?php

namespace App\Repositories\Term;

use TestCase;

class TermRepositoryTest extends TestCase
{
    private $terms;

    public function setUp()
    {
        parent::setUp();
        $this->terms = \App::make(TermRepository::class);
    }

  /** @test **/
  public function it_gets_terms_by_taxonomy()
  {
      $categories = factory(\App\Term::class, 3)->create(['taxonomy' => 'category']);

      $this->assertCount(3, $this->terms->getCategories());
      $this->assertEmpty($this->terms->getTags());
  }
}
