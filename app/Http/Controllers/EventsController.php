<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function show(Event $event)
    {
    	return view('events.show', compact('event'));
    }
}
