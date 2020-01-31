<?php

namespace App\Notifications\Sale;

use App\Abstracts\Notification;
use Illuminate\Support\Facades\URL;

class Invoice extends Notification
{
    /**
     * The invoice model.
     *
     * @var object
     */
    public $invoice;

    /**
     * The email template.
     *
     * @var string
     */
    public $template;

    /**
     * Create a notification instance.
     *
     * @param  object  $invoice
     * @param  object  $template
     */
    public function __construct($invoice = null, $template = null)
    {
        parent::__construct();

        $this->invoice = $invoice;
        $this->template = $template;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = $this->initMessage();

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

    public function getTags()
    {
        return [
            '{invoice_number}',
            '{invoice_total}',
            '{invoice_due_date}',
            '{invoice_guest_link}',
            '{invoice_admin_link}',
            '{invoice_portal_link}',
            '{customer_name}',
            '{company_name}',
        ];
    }

    public function getTagsReplacement()
    {
        return [
            $this->invoice->invoice_number,
            money($this->invoice->amount, $this->invoice->currency_code, true),
            company_date($this->invoice->due_at),
            URL::signedRoute('signed.invoices.show', [$this->invoice->id, 'company_id' => $this->invoice->company_id]),
            route('invoices.show', $this->invoice->id),
            route('portal.invoices.show', $this->invoice->id),
            $this->invoice->contact_name,
            $this->invoice->company->name
        ];
    }
}
