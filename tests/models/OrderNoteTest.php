<?php

namespace App;

use TestCase;

class OrderNoteTest extends TestCase
{
    /** @test **/
    public function it_gets_the_icon_for_a_note()
    {
        $note = new OrderNote([
            'order_id' => 1,
            'key'      => 'payment_completed',
            'body'     => 'foo',
            ]);
        $this->assertEquals('credit-card', $note->icon);
        $note->key = 'some_unknown_key';
        $this->assertEquals('envelope', $note->icon);
    }
}
