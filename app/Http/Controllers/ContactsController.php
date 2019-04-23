<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Mail\ContactSubmitted;

class ContactsController extends Controller
{
  public function index()
  {
    return view('contacts.index');
  }

}
