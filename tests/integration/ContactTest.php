<?php

namespace integration;

use App\User;
use MailThief\Facades\MailThief;
use TestCase;

class ContactTest extends TestCase
{
    /** @test */
  public function it_sends_an_email_from_the_contact_page()
  {
      // Make an admin user to send email to
    factory(User::class)->create();

      MailThief::hijack();

      $this->visit('/contact')
        ->type('Joe Bloggs', 'name')
        ->type('joe@example.com', 'email')
        ->type('This is an email', 'subject')
        ->type('Lorem Ipsum', 'message')
        ->press('send');

      $admin_users = User::shopAdmins()->get();

      $this->assertTrue(MailThief::hasMessageFor($admin_users->first()->email));

      $message = MailThief::lastMessage();
      $this->assertEquals('This is an email', $message->subject);

      $this->assertEquals('Joe Bloggs', $message->from->first());
      $this->assertEquals('joe@example.com', $message->from->keys()->first());

      $this->seePageIs('/contact')
         ->see('your message has been sent');
  }
}
