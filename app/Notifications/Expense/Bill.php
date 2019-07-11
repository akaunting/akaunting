<?php

namespace App\Notifications\Expense;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Bill extends Notification
{
    /**
     * The invoice model.
     *
     * @var object
     */
    public $bill;

    /**
     * Create a notification instance.
     *
     * @param  object  $bill
     */
    public function __construct($bill)
    {
        $this->queue = 'high';
        $this->delay = config('queue.connections.database.delay');

        $this->bill = $bill;
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
        $message = (new MailMessage)
            ->line('You are receiving this email because you have an upcoming ' . money($this->bill->amount, $this->bill->currency_code, true) . ' bill to ' . $this->bill->vendor_name . ' vendor.')
            ->action('Add Payment', url('expenses/bills', $this->bill->id));

        // Override per company as Laravel doesn't read config
        $message->from(config('mail.from.address'), config('mail.from.name'));

        return $message;
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
            'bill_id' => $this->bill->id,
            'amount' => $this->bill->amount,
        ];
    }
}
