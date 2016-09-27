<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged
{
    public $orderId;
    public $from;
    public $to;

    use InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orderId, $from, $to)
    {
        $this->orderId = $orderId;
        $this->from = $from;
        $this->to = $to;
    }
}
