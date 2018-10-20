<?php

namespace App\Notifications;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderDispatched extends Notification
{
    use Queueable;

    protected $order;
    protected $trackingLink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, $trackingLink)
    {
        $this->order = $order;
        $this->trackingLink = $trackingLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
                    ->greeting(sprintf('Dear %s,', $this->order->user->name))
                    ->subject('Order dispatched')
                    ->line('Your order has been dispatched and should be with you soon.')
                    ->line(sprintf('Order ID: %s', $this->order->id))
                    ->line(sprintf('Order date: %s', $this->order->created_at->format('j M Y G:i')));

        if ($this->trackingLink) {
            $message->action('Track your package', $this->trackingLink);
        }

        return $message->line('Thank you for buying from Samarkand Design.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
