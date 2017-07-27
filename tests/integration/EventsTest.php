<?php

namespace Integration;

use TestCase;
use App\Event;
use Carbon\Carbon;

class EventsTest extends TestCase
{
    /** @test **/
  public function it_shows_an_event()
  {
      $event = factory(Event::class)->create();

      $this->visit("/event/{$event->slug}")
         ->see($event->title);
  }

  /** @test **/
  public function it_lists_upcoming_events()
  {
      $upcomingEvent = factory(Event::class)->create(['start_date' => Carbon::now()->addWeek()]);
      $pastEvent = factory(Event::class)->create(['end_date' => Carbon::now()->subWeek()]);

      $this->visit('/events')
    ->seePageIs('/events')
    ->see($upcomingEvent->title)
    ->dontSee($pastEvent->title);
  }
}
