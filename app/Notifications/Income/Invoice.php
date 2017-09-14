<?php

namespace App\Notifications\Income;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Invoice extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    /**
     * Create a notification instance.
     *
     * @param  object  $invoice
     * @return void
     */
    public function __construct($invoice)
    {
        $this->queue = 'high';
        $this->delay = config('queue.connections.database.delay');

        $this->invoice = $invoice;
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
            ->line('You are receiving this email because you have an upcoming ' . money($this->invoice->amount, $this->invoice->currency_code, true) . ' invoice to ' . $this->invoice->customer->name . ' customer.')
            ->action('Pay Now', url('customers/invoices', $this->invoice->id, true));
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
            'invoice_id' => $this->invoice->id,
            'amount' => $this->invoice->amount,
        ];
    }
}
