<?php

namespace App;

use App\Event;
use Carbon\Carbon;
use TestCase;

class EventTest extends TestCase
{
    /** @test **/
    public function it_sets_the_dates_appropriately_for_all_day_events()
    {
        $allDayEvent = factory(Event::class)->make([
            'all_day' => true,
            'start_date' => Carbon::create(2016, 11, 16, 5, 0, 0),
            'end_date' => Carbon::create(2016, 11, 17, 15, 0, 0),
            ]);

        $timedEvent = factory(Event::class)->make([
            'all_day' => false,
            'start_date' => Carbon::create(2016, 11, 16, 5, 0, 0),
            'end_date' => Carbon::create(2016, 11, 17, 15, 0, 0),
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
            'all_day' => true,
            'start_date' => Carbon::now()->addHour(),
            'end_date' => Carbon::now()->addDay(),
            ]);

        $this->assertTrue($event->isUnderway());
        $this->assertFalse($event->isUpcoming());
        $this->assertFalse($event->hasEnded());
    }

    /** @test **/
    public function it_gets_the_duration_of_an_all_day_event()
    {
        $event = factory(Event::class)->make([
            'all_day' => true,
            'start_date' => Carbon::create(2016, 11, 16, 5, 0, 0),
            'end_date' => Carbon::create(2016, 11, 17, 15, 0, 0),
            ]);

        $this->assertEquals('2 days', $event->duration());
    }
}
