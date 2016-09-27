<?php

namespace App\Events;

use App\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

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
