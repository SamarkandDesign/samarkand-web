<?php

use MailThief\Testing\InteractsWithMail;

class MailerTest extends TestCase
{
    use InteractsWithMail;

    /** @test **/
    public function it_sends_an_email_to_a_user()
    {
        $user = factory(\App\User::class)->create();
        $mailer = app(\App\Mailers\Mailer::class);

        $mailer->sendTo($user, 'This is a subject', 'emails.default', ['body' => 'This is the email body']);

        $this->seeMessageFor($user->email);
        $this->seeMessageWithSubject('This is a subject');
        $this->assertTrue($this->lastMessage()->contains('This is the email body'));
    }
}
