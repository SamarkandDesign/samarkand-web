<?php

namespace App\Mailers;

use App\Contact;
use App\User;

class ContactMailer extends Mailer
{
    /**
     * Send a contact email to each of the admins.
     *
     * @param Order $order
     *
     * @return void
     */
    public function sendContactEmail(Contact $contact)
    {
        $recipientEmail = config('mail.recipients.contact');

        if ($recipientEmail) {
            $this->mail->queue(['text' => 'emails.plaintext'], ['text' => $contact->message], function ($message) use ($recipientEmail, $contact) {
                $message->to($recipientEmail)
                      ->from($contact->email, $contact->name)
                      ->subject($contact->subject);
            });
        }
    }
}
