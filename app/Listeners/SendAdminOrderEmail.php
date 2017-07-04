<?php

namespace App\Listeners;

use App\Events\OrderWasPaid;
use App\Mail\OrderConfirmedForAdmin;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdminOrderEmail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param OrderWasPaid $event
     *
     * @return void
     */
    public function handle(OrderWasPaid $event)
    {
        $order = $event->order;
        $admins = User::shopAdmins()->get();

        foreach ($admins as $admin) {
            \Mail::to($admin)->send(new OrderConfirmedForAdmin($order));
        }
    }
}
