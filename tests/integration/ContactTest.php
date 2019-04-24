<?php

namespace Integration;

use App\User;
use TestCase;
use App\Mail\ContactSubmitted;

class ContactTest extends TestCase
{
  /** @test */
  public function it_shows_the_contact_page()
  {
    $response = $this->get('/contact');

    $response->assertSee('Contact Us');
  }
}
