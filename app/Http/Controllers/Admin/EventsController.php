<?php

namespace App\Http\Controllers\Admin;

use App\Address;
use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->has('all') ? Event::paginate() : Event::upcoming()->paginate();

        return view('admin.events.index', compact('events'));
    }

    public function create(Event $event)
    {
        // dd(\App\Address::pluck('line_1', 'id'));
        return view('admin.events.create', compact('event'));
    }

    public function store(CreateEventRequest $request)
    {
        if ($request->get('create_new_venue')) {
            $venue = $this->createVenue($request);
            $request->merge(['address_id' => $venue->id]);
        }

        $event = Event::create($request->all());

        return redirect()->route('admin.events.index')->with([
          'alert'       => 'Event Created!',
          'alert-class' => 'success',
          ]);
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        if ($request->get('create_new_venue')) {
            $venue = $this->createVenue($request);
            $request->merge(['address_id' => $venue->id]);
        }

        $event->update($request->all());

        return redirect()->back()->with([
            'alert'       => 'Event Updated!',
            'alert-class' => 'success',
            ]);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with([
            'alert'       => 'Event Deleted',
            'alert-class' => 'success',
            ]);
    }

    protected function createVenue($request) : Address
    {
        $rules = collect(Address::$rules)->map(function ($rule, $key) {
            return ['field' => 'address.'.$key, 'rule' => $rule];
        })->pluck('rule', 'field')->toArray();

        $this->validate($request, $rules);
        $address = new Address($request->get('address'));
        $address->name = '';
        $address->addressable_id = 0;
        $address->addressable_type = 'App\Event';
        $address->save();

        return $address;
    }
}
