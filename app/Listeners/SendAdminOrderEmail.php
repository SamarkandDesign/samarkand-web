<?php

namespace App\Listeners;

use App\User;
use App\Events\OrderWasPaid;
use App\Mail\OrderConfirmedForAdmin;
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
      \Log::info('Emailing admin order notification', [
        'recipient' => $admin->email,
        'order_id' => $order->id,
      ]);

      \Mail::to($admin)->send(new OrderConfirmedForAdmin($order));
    }
  }
}
