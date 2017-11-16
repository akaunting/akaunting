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
        $message = (new MailMessage)
            ->line(trans('invoices.notification.message', ['amount' => money($this->invoice->amount, $this->invoice->currency_code, true), 'customer' => $this->invoice->customer->name]))
            ->action(trans('invoices.notification.button'), url('customers/invoices', $this->invoice->id, true));

        // Attach the PDF file if available
        if (isset($this->invoice->pdf_path)) {
            $message->attach($this->invoice->pdf_path, [
                'mime' => 'application/pdf',
            ]);
        }

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
            'invoice_id' => $this->invoice->id,
            'amount' => $this->invoice->amount,
        ];
    }
}
