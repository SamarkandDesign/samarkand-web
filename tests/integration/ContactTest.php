<?php

namespace Integration;

use App\User;
use TestCase;
use MailThief\Testing\InteractsWithMail;

class ContactTest extends TestCase
{
    use InteractsWithMail;

  /** @test */
  public function it_sends_an_email_from_the_contact_page_and_stores_the_message()
  {
      config(['mail.recipients.contact' => 'foo@example.com']);

      // Make an admin user to send email to
      $user = factory(User::class)->create(['is_shop_manager' => true]);

      $this->visit('/contact')
          ->type('Joe Bloggs', 'name')
          ->type('joe@example.com', 'email')
          ->type('This is an email', 'subject')
          ->type('Lorem Ipsum', 'message')
          ->press('send');

      $this->seeMessageFor('foo@example.com');

      $this->seeMessageWithSubject('This is an email');
      $this->seeMessageFrom('Joe Bloggs');

      $this->seePageIs('/contact')
           ->see('your message has been sent');

      $this->seeInDatabase('contacts', ['message' => 'Lorem Ipsum', 'subject' => 'This is an email']);
  }

  /** @test */
  public function it_validates_the_contact_form()
  {
      $this->visit('/contact')
    ->press('send')
    ->seePageIs('/contact')
    ->see('email field is required');
  }
}
