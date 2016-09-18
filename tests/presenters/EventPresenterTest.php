<?php

use App\Event;
use Carbon\Carbon;

class EventPresenterTest extends TestCase
{
    /** @test **/
    public function it_presents_the_google_calendar_link()
    {
        $event = factory(Event::class)->make();

        $this->assertContains('google.com/calendar', $event->present()->googleCalendarLink());
    }

    /** @test **/
    public function it_gets_the_duration_of_an_all_day_event()
    {
        $event = factory(Event::class)->make([
            'all_day'    => true,
            'start_date' => Carbon::create(2016, 11, 16, 5, 0, 0),
            'end_date'   => Carbon::create(2016, 11, 17, 15, 0, 0),
            ]);

        $this->assertEquals('2 days', $event->present()->duration());
    }
}
