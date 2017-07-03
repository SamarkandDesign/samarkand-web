<?php

namespace Integration\Admin;

use App\Address;
use App\Event;
use Carbon\Carbon;
use TestCase;

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
        $this->markTestSkipped();
        $address = factory(Address::class)->create([
            'addressable_type' => 'App\Event',
            'addressable_id'   => 1,
            ]);

        $response = $this->get('/admin/events/create')
        ->type('Foo Event', 'title')
        ->type('Foo event content', 'description')
        ->check('all_day')
        ->type(Carbon::now()->addWeek()->format('Y-m-d\TH:i:s'), 'start_date')
        ->type(Carbon::now()->addWeeks(2)->format('Y-m-d\TH:i:s'), 'end_date')
        ->select($address->id, 'address_id')
        ->press('Submit');

        $this->assertDatabaseHas('events', [
            'title' => 'Foo Event',
            ]);
    }

    /** @test **/
    public function it_creates_a_new_venue_for_an_event()
    {
        $this->markTestSkipped();
        $response = $this->get('/admin/events/create')
        ->type('Foo Event', 'title')
        ->type('Foo event content', 'description')
        ->check('all_day')
        ->type('2016-10-22', 'start_date')
        ->type('2016-10-22', 'end_date')
        ->check('create_new_venue')
        ->type('Buckingham Palace', 'address[line_1]')
        ->type('London', 'address[city]')
        ->type('SW1 4NQ', 'address[postcode]')
        ->select('GB', 'address[country]')
        ->press('Submit');

        $venue = Address::where('line_1', 'Buckingham Palace')->first();
        $this->assertTrue($venue->exists());

        $this->assertDatabaseHas('events', [
            'title'      => 'Foo Event',
            'address_id' => $venue->id,
            ]);
    }

    /** @test **/
    public function it_can_edit_an_event()
    {
        $this->markTestSkipped();
        $event = factory(Event::class)->create();

        $response = $this->get("/admin/events/{$event->id}/edit")
        ->type('Foo Event', 'title')
        ->type('Foo event content', 'description')
        ->check('all_day')
        ->press('Submit');

        $this->assertDatabaseHas('events', [
            'title' => 'Foo Event',
            ]);
    }

    /** @test **/
    public function it_edits_an_address_to_have_a_new_venue()
    {
        $this->markTestSkipped();
        $event = factory(Event::class)->create();

        $response = $this->get("/admin/events/{$event->id}/edit")
        ->type('Foo2 Event', 'title')
        ->type('Foo event content', 'description')
        ->check('all_day')
        ->check('create_new_venue')
        ->type('Buckingham Palace2', 'address[line_1]')
        ->type('London', 'address[city]')
        ->type('SW1 4NQ', 'address[postcode]')
        ->select('GB', 'address[country]')
        ->press('Submit');

        $venue = Address::where('line_1', 'Buckingham Palace2')->first();
        $this->assertTrue($venue->exists());

        $this->assertDatabaseHas('events', [
            'title'      => 'Foo2 Event',
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
