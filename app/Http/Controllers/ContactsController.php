<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Mailers\ContactMailer;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request, ContactMailer $mailer)
    {
        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $contact = Contact::create($request->all());
        $mailer->sendContactEmail($contact);

        return redirect()->back()->with(['alert' => 'Thanks, your message has been sent', 'alert-class' => 'success']);
    }
}
