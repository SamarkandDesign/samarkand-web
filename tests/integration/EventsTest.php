<?php

namespace Integration;

use App\Event;
use Carbon\Carbon;
use TestCase;

class EventsTest extends TestCase
{
    /** @test **/
  public function it_shows_an_event()
  {
      $event = factory(Event::class)->create();

      $response = $this->get("/event/{$event->slug}");
      $this->assertContains($event->title, $response->getContent());
  }

  /** @test **/
  public function it_lists_upcoming_events()
  {
      $upcomingEvent = factory(Event::class)->create(['start_date' => Carbon::now()->addWeek()]);
      $pastEvent = factory(Event::class)->create(['end_date' => Carbon::now()->subWeek()]);

      $response = $this->get('/events');

    $this->assertContains($upcomingEvent->title, $response->getContent());
    $this->assertNotContains($pastEvent->title, $response->getContent());
  }
}
