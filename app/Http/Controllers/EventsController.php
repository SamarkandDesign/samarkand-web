<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventsController extends Controller
{
  public function show(Event $event)
  {
    return view('events.show', compact('event'));
  }

  public function index(Request $request)
  {
    $this->validate($request, ['before' => 'date']);
    $before = $request->get('before');
    $events = $before
      ? Event::before(Carbon::parse($before))->get()
      : Event::upcoming()->paginate();

    return view('events.index', [
      'events' => $events,
      'earliestDate' => $this->getEarliestDate($events, $before),
    ]);
  }

  protected function getEarliestDate($events, $before)
  {
    if (!$events->count()) {
      return $before ? $before : Carbon::now()->format('Y-m-d');
    }

    return $events->min('start_date')->format('Y-m-d');
  }
}
