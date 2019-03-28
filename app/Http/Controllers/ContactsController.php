<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Mail\ContactSubmitted;

class ContactsController extends Controller
{
  public function create()
  {
    return view('contacts.create');
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required',
      'email' => 'required|email',
      'subject' => 'required',
      'message' => 'required',
      'website' => 'size:0',
    ]);

    $contact = Contact::create($request->all());
    $recipient = config('mail.recipients.contact');
    if ($recipient) {
      \Mail::to($recipient)->send(new ContactSubmitted($contact));
    } else {
      \Log::warning('Not sending contact email as recipient not set', ['contact' => $contact->toArray()]);
    }

    return redirect('/contact')->with([
      'alert' => 'Thanks, your message has been sent',
      'alert-class' => 'success',
    ]);
  }
}
