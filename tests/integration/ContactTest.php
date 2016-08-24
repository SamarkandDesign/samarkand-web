<?php

namespace integration;

use App\User;
use MailThief\Testing\InteractsWithMail;
use TestCase;

class ContactTest extends TestCase
{
  use InteractsWithMail;

    /** @test */
  public function it_sends_an_email_from_the_contact_page()
  {
      // Make an admin user to send email to
    factory(User::class)->create();

      $this->visit('/contact')
          ->type('Joe Bloggs', 'name')
          ->type('joe@example.com', 'email')
          ->type('This is an email', 'subject')
          ->type('Lorem Ipsum', 'message')
          ->press('send');

      $admin_users = User::shopAdmins()->get();

      $this->seeMessageFor($admin_users->first()->email);


      $this->seeMessageWithSubject('This is an email');
      $this->seeMessageFrom('Joe Bloggs');

      $this->seePageIs('/contact')
           ->see('your message has been sent');
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
