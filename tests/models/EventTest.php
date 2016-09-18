<?php

namespace App;

use Carbon\Carbon;
use TestCase;

class EventTest extends TestCase
{
    /** @test **/
    public function it_sets_the_dates_appropriately_for_all_day_events()
    {
        $allDayEvent = factory(Event::class)->make([
            'all_day'    => true,
            'start_date' => Carbon::create(2016, 11, 16, 5, 0, 0),
            'end_date'   => Carbon::create(2016, 11, 17, 15, 0, 0),
            ]);

        $timedEvent = factory(Event::class)->make([
            'all_day'    => false,
            'start_date' => Carbon::create(2016, 11, 16, 5, 0, 0),
            'end_date'   => Carbon::create(2016, 11, 17, 15, 0, 0),
            ]);

        $this->assertEquals(0, $allDayEvent->start_date->hour);
        $this->assertEquals(23, $allDayEvent->end_date->hour);

        $this->assertEquals(5, $timedEvent->start_date->hour);
        $this->assertEquals(15, $timedEvent->end_date->hour);
    }

    /** @test **/
    public function it_checks_the_event_status_for_an_underway_event()
    {
        $event = factory(Event::class)->make([
            'all_day'    => true,
            'start_date' => Carbon::now()->addHour(),
            'end_date'   => Carbon::now()->addDay(),
            ]);

        $this->assertTrue($event->isUnderway());
        $this->assertFalse($event->isUpcoming());
        $this->assertFalse($event->hasEnded());
    }

    /** @test **/
    public function it_gets_previous_events()
    {
        $futureEvent = factory(Event::class)->create([
            'address_id' => 1,
            'start_date' => Carbon::now()->addDay(),
            'end_date'   => Carbon::now()->addDays(2),
            ]);        

        $recentEvent = factory(Event::class)->create([
            'title' => 'recent event',
            'address_id' => 1,
            'start_date' => Carbon::now()->subDays(3),
            'end_date'   => Carbon::now()->subDays(2),
            ]);        

        $farEvent = factory(Event::class)->create([
            'title' => 'far event',
            'address_id' => 1,
            'start_date' => Carbon::now()->subDays(32),
            'end_date'   => Carbon::now()->subDays(31),
            ]);

        $pastEvents = Event::before()->get();

        $this->assertEquals($recentEvent->id, $pastEvents->first()->id);
        $this->assertCount(2, $pastEvents);

        $olderEvents = Event::before($recentEvent->start_date)->get();

        $this->assertEquals($farEvent->id, $olderEvents->first()->id);
        $this->assertCount(1, $olderEvents);
    }
}
