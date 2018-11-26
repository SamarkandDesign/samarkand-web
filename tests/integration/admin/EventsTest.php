<?php

namespace Integration\Admin;

use TestCase;
use App\Event;
use App\Address;
use Carbon\Carbon;

class EventsTest extends TestCase
{
  private $user;

  public function setUp()
  {
    parent::setUp();
    $this->user = $this->logInAsAdmin();
  }

  /** @test **/
  public function it_shows_upcoming_events_in_the_admin_area()
  {
    $upcomingEvent = factory(Event::class)->create(['start_date' => Carbon::now()->addWeek()]);
    $pastEvent = factory(Event::class)->create(['end_date' => Carbon::now()->subWeek()]);

    $response = $this->get('/admin/events');

    $this->assertContains($upcomingEvent->title, $response->getContent());
    $this->assertNotContains($pastEvent->title, $response->getContent());
  }

  /** @test **/
  public function it_shows_all_events_in_the_admin_area()
  {
    $upcomingEvent = factory(Event::class)->create(['start_date' => Carbon::now()->addWeek()]);
    $pastEvent = factory(Event::class)->create(['end_date' => Carbon::now()->subWeek()]);

    $response = $this->get('/admin/events?all=1');
    $this->assertContains($upcomingEvent->title, $response->getContent());
    $this->assertContains($pastEvent->title, $response->getContent());
  }

  /** @test **/
  public function it_can_create_an_event()
  {
    $address = factory(Address::class)->create([
      'addressable_type' => 'App\Event',
      'addressable_id' => 1,
    ]);

    $response = $this->get('/admin/events/create');
    $response->assertStatus(200);
    $this->post('/admin/events', [
      'title' => 'Foo Event',
      'description' => 'Foo event content',
      'all_day' => true,
      'start_date' => Carbon::now()
        ->addWeek()
        ->format('Y-m-d\TH:i:s'),
      'end_date' => Carbon::now()
        ->addWeeks(2)
        ->format('Y-m-d\TH:i:s'),
      'address_id' => $address->id,
    ]);

    $this->assertDatabaseHas('events', [
      'title' => 'Foo Event',
    ]);
  }

  /** @test **/
  public function it_creates_a_new_venue_for_an_event()
  {
    $response = $this->get('/admin/events/create');
    $response = $this->post('/admin/events', [
      'title' => 'Foo Event',
      'description' => 'Foo event content',
      'all_day' => true,
      'start_date' => '2016-10-22',
      'end_date' => '2016-10-22',
      'create_new_venue' => true,
      'address' => [
        'line_1' => 'Buckingham Palace',
        'city' => 'London',
        'postcode' => 'SW1 4NQ',
        'country' => 'GB',
      ],
    ]);

    $venue = Address::where('line_1', 'Buckingham Palace')->first();
    $this->assertDatabaseHas('addresses', [
      'line_1' => 'Buckingham Palace',
    ]);

    $this->assertDatabaseHas('events', [
      'title' => 'Foo Event',
      'address_id' => $venue->id,
    ]);
  }

  /** @test **/
  public function it_can_edit_an_event()
  {
    $event = factory(Event::class)->create();

    $response = $this->get("/admin/events/{$event->id}/edit");

    $response = $this->patch(
      "/admin/events/{$event->id}",
      array_merge($event->toArray(), [
        'title' => 'Foo Event',
        'description' => 'Foo event content',
        'all_day' => true,
      ])
    );

    $this->assertDatabaseHas('events', [
      'title' => 'Foo Event',
    ]);
  }

  /** @test **/
  public function it_edits_an_address_to_have_a_new_venue()
  {
    $event = factory(Event::class)->create();

    $response = $this->get("/admin/events/{$event->id}/edit");
    $response = $this->patch(
      "/admin/events/{$event->id}",
      array_merge($event->toArray(), [
        'title' => 'Foo2 Event',
        'description' => 'Foo event content',
        'all_day' => true,
        'create_new_venue' => true,
        'address' => [
          'line_1' => 'Buckingham Palace2',
          'city' => 'London',
          'postcode' => 'SW1 4NQ',
          'country' => 'GB',
        ],
      ])
    );

    $venue = Address::where('line_1', 'Buckingham Palace2')->first();
    $this->assertTrue($venue->exists());

    $this->assertDatabaseHas('events', [
      'title' => 'Foo2 Event',
      'address_id' => $venue->id,
    ]);
  }

  /** @test **/
  public function it_deletes_an_event()
  {
    $event = factory(Event::class)->create();

    $response = $this->delete("/admin/events/{$event->id}");

    $response->assertRedirect('/admin/events');
    $this->assertNotContains($event->title, $this->get('/admin/events')->getContent());
  }
}
