<?php

namespace App\Events;

use App\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class PaymentFailed
{
    public $order;
    public $message;

    use InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order, $message)
    {
        //
        $this->order = $order;
        $this->message = $message;
    }
}
