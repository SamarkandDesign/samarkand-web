<?php

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        $url = url("/admin/orders/{$this->order->id}");

        $template = '*New Order!*

*Customer:* {{customer}}
*Total:* £{{total}}

*Items:*
{{items}}
';

        $parts = [
            '{{customer}}' => $this->order->user->name,
            '{{total}}' => $this->order->amount->asDecimal(),
            '{{items}}' => $this->orderItems(),
        ];

        return TelegramMessage::create()
            ->to($notifiable->telegram_id)
            ->content(strtr($template, $parts)) // Markdown supported.
            ->button('View Order', $url); // Inline Button
    }

    protected function orderItems() {
        return $this->order->items->map(function($item) {
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
