<?php

namespace App\Mail;

use App\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSubmitted extends Mailable
{
  public $contact;

  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(Contact $contact)
  {
    $this->contact = $contact;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->text('emails.plaintext')
      ->with(['text' => $this->contact->message])
      ->subject($this->contact->subject)
      ->from($this->contact->email, $this->contact->name);
  }
}
