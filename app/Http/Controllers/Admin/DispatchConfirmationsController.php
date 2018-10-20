<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\OrderNote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\OrderDispatched;

class DispatchConfirmationsController extends Controller
{
    /**
     * Send an order dispatch confirmation email.
     *
     * @param Order $order
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Order $order, Request $request)
    {
        $this->validate($request, [
            'tracking_link' => 'url',
        ]);

        if ($order->status !== 'processing') {
            return redirect()->route('admin.orders.show', $order)
                ->with('alert', 'Order must be processing to send a confirmation')
                ->with('alert-class', 'danger');
        }

        $trackingLink = $request->get('tracking_link');

        $order->update(['status' => 'completed']);
        $order->user->notify(new OrderDispatched($order, $trackingLink));

        $orderNoteBody = $trackingLink
            ? sprintf('Dispatch confirmation sent to %s with tracking link %s', $order->user->email, $trackingLink)
            : sprintf('Dispatch confirmation sent to %s no tracking link', $order->user->email);

        OrderNote::create([
            'order_id' => $order->id,
            'key'      => 'notification_sent',
            'body'     => $orderNoteBody,
            'user_id'  => \Auth::user()->id,
            ]);

        return redirect()->route('admin.orders.show', $order)
                         ->withAlert('Confirmation sent')
                         ->with('alert-class', 'success');
    }
}
