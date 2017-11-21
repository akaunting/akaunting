<?php

namespace App\Notifications\Item;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Item extends Notification
{
    /**
     * The item model.
     *
     * @var object
     */
    public $item;

    /**
     * Create a notification instance.
     *
     * @param  object  $item
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(trans('items.notification.message', ['name' => $this->item->name]))
            ->action(trans('items.notification.button'), url('items/items', $this->item->id, true));
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
            'item_id' => $this->item->id,
            'name' => $this->item->name,
        ];
    }
}
