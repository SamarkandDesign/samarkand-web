<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Mail\ContactSubmitted;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $contact = Contact::create($request->all());
        \Mail::to(config('mail.recipients.contact'))->send(new ContactSubmitted($contact));

        return redirect('/contact')->with([
            'alert'       => 'Thanks, your message has been sent',
            'alert-class' => 'success',
            ]);
    }
}
