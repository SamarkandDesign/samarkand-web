<?php

namespace App\Mailers;

use App\Contact;
use App\User;

class ContactMailer extends Mailer
{
    /**
     * Send a contact email to each of the admins
     *
     * @param Order $order
     *
     * @return void
     */
    public function sendContactEmail(Contact $contact)
    {
        $admins = User::shopAdmins()->get();

        foreach ($admins as $admin) {
          $this->mail->queue('emails.plain', ['body' => $contact->message], function ($message) use ($admin, $contact) {
              $message->to($admin->email)
                      ->from($contact->email, $contact->name)
                      ->subject($contact->subject);
          });
        }
    }
}
