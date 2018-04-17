<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class OrderCreated extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(\App\Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->telegram_id ? ['mail', TelegramChannel::class] : ['mail'];
    }

    public function toTelegram($notifiable)
    {
        $url = url("/admin/orders/{$this->order->id}");

        $template = "*New Customer Order #{$this->order->id}*

*Customer:* {$this->order->user->name}
*Total:* £{$this->order->amount->asDecimal()}

*Items:*
{$this->orderItems()}
";

        return TelegramMessage::create()
            ->to($notifiable->telegram_id)
            ->content(strtr($template, $parts)) // Markdown supported.
            ->button("View Order {$this->order->id}", $url); // Inline Button
    }

    protected function orderItems()
    {
        return $this->order->items->map(function ($item) {
            return sprintf('%sx %s', $item->quantity, $item->orderable->name);
        })->implode("\n");
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('emails.orders.admin_notification', ['order' => $this->order])
            ->subject("New Customer Order #{$this->order->id}");
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
