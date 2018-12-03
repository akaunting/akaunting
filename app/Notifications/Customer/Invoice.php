<?php

namespace App\Notifications\Customer;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Invoice extends Notification
{
    /**
     * The bill model.
     *
     * @var object
     */
    public $invoice;

    public $invoice_payment;

    /**
     * Create a notification instance.
     *
     * @param  object  $invoice
     */
    public function __construct($invoice, $invoice_payment)
    {
        $this->queue = 'high';
        $this->delay = config('queue.connections.database.delay');

        $this->invoice = $invoice;
        $this->invoice_payment = $invoice_payment;
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
            ->line(trans('customers.notification.message', ['invoice_number' => $this->invoice->invoice_number, 'amount' => money($this->invoice_payment->amount, $this->invoice_payment->currency_code, true), 'customer' => $this->invoice->customer_name]));

        // Override per company as Laravel doesn't read config
        $message->from(config('mail.from.address'), config('mail.from.name'));

        // Attach the PDF file if available
        if (isset($this->invoice->pdf_path)) {
            $message->attach($this->invoice->pdf_path, [
                'mime' => 'application/pdf',
            ]);
        }

        $message->action(trans('customers.notification.button'), url('incomes/invoices', $this->invoice->id));

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
