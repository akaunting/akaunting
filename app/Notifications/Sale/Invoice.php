<?php

namespace App\Notifications\Sale;

use App\Abstracts\Notification;
use App\Models\Common\EmailTemplate;
use App\Traits\Documents;
use Illuminate\Support\Facades\URL;

class Invoice extends Notification
{
    use Documents;

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
     * Should attach pdf or not.
     *
     * @var bool
     */
    public $attach_pdf;

    /**
     * Create a notification instance.
     *
     * @param  object  $invoice
     * @param  object  $template_alias
     * @param  object  $attach_pdf
     */
    public function __construct($invoice = null, $template_alias = null, $attach_pdf = false)
    {
        parent::__construct();

        $this->invoice = $invoice;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
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

        // Attach the PDF file
        if ($this->attach_pdf) {
            $message->attach($this->storeDocumentPdfAndGetPath($this->invoice), [
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
            'template_alias' => $this->template->alias,
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->document_number,
            'customer_name' => $this->invoice->contact_name,
            'amount' => $this->invoice->amount,
            'invoiced_date' => company_date($this->invoice->issued_at),
            'invoice_due_date' => company_date($this->invoice->due_at),
            'status' => $this->invoice->status,
        ];
    }

    public function getTags()
    {
        return [
            '{invoice_number}',
            '{invoice_total}',
            '{invoice_amount_due}',
            '{invoiced_date}',
            '{invoice_due_date}',
            '{invoice_guest_link}',
            '{invoice_admin_link}',
            '{invoice_portal_link}',
            '{customer_name}',
            '{company_name}',
            '{company_email}',
            '{company_tax_number}',
            '{company_phone}',
            '{company_address}',
        ];
    }

    public function getTagsReplacement()
    {
        return [
            $this->invoice->document_number,
            money($this->invoice->amount, $this->invoice->currency_code, true),
            money($this->invoice->amount_due, $this->invoice->currency_code, true),
            company_date($this->invoice->issued_at),
            company_date($this->invoice->due_at),
            URL::signedRoute('signed.invoices.show', [$this->invoice->id]),
            route('invoices.show', $this->invoice->id),
            route('portal.invoices.show', $this->invoice->id),
            $this->invoice->contact_name,
            $this->invoice->company->name,
            $this->invoice->company->email,
            $this->invoice->company->tax_number,
            $this->invoice->company->phone,
            nl2br(trim($this->invoice->company->address)),
        ];
    }
}
