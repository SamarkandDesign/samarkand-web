<?php

namespace Integration;

use App\Event;
use TestCase;

class EventsTest extends TestCase
{
  /** @test **/
  public function it_shows_an_event()
  {
    $event = factory(Event::class)->create();

    $this->visit("/event/{$event->slug}")
         ->see($event->title);
  }
}
