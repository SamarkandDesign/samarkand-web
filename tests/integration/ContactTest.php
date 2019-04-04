<?php

namespace Integration;

use App\User;
use TestCase;
use App\Mail\ContactSubmitted;

class ContactTest extends TestCase
{
  /** @test */
  public function it_sends_an_email_from_the_contact_page_and_stores_the_message()
  {
    \Mail::fake();
    config(['mail.recipients.contact' => 'foo@example.com']);

    // Make an admin user to send email to
    $user = factory(User::class)->create(['is_shop_manager' => true]);

    $response = $this->post('/contact', [
      'name' => 'Joe Bloggs',
      'email' => 'joe@example.com',
      'subject' => 'This is an email',
      'message' => 'Lorem Ipsum',
      'website' => '',
    ]);

    \Mail::assertSent(ContactSubmitted::class, function ($m) {
      return $m->hasTo('foo@example.com');
    });
    $response->assertRedirect('/contact');

    $this->assertDatabaseHas('contacts', [
      'message' => 'Lorem Ipsum',
      'subject' => 'This is an email',
    ]);
  }

  /** @test */
  public function it_prevents_spam_submissions()
  {
    \Mail::fake();
    $response = $this->post('/contact', [
      'name' => 'Bad',
      'email' => 'spammer@botfarm.com',
      'subject' => 'Some spammy subject',
      'message' => 'Some spammy subject',
      'website' => 'http://badsite.com',
    ]);

    \Mail::assertNotSent(ContactSubmitted::class);
    $response->assertRedirect('/contact');
  }
}
