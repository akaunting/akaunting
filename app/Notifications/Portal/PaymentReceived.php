<?php

namespace App\Notifications\Portal;

use App\Abstracts\Notification;
use App\Models\Banking\Transaction;
use App\Models\Setting\EmailTemplate;
use App\Models\Document\Document;
use App\Traits\Documents;
use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class PaymentReceived extends Notification
{
    use Documents;

    /**
     * The bill model.
     *
     * @var object
     */
    public $invoice;

    /**
     * The payment transaction.
     *
     * @var Transaction
     */
    public $transaction;

    /**
     * The email template.
     *
     * @var EmailTemplate
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
     */
    public function __construct(Document $invoice = null, Transaction $transaction = null, string $template_alias = null, bool $attach_pdf = false)
    {
        parent::__construct();

        $this->invoice = $invoice;
        $this->transaction = $transaction;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $message = $this->initMailMessage();

        // Attach the PDF file
        if ($this->attach_pdf) {
            $func = is_local_storage() ? 'fromPath' : 'fromStorage';

            $path = $this->storeDocumentPdfAndGetPath($this->invoice);
            $file = Attachment::$func($path)->withMime('application/pdf');

            $message->attach($file);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        $this->initArrayMessage();

        return [
            'template_alias' => $this->template->alias,
            'title' => trans('notifications.menu.' . $this->template->alias . '.title'),
            'description' => trans('notifications.menu.' . $this->template->alias . '.description', $this->getTagsBinding()),
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->document_number,
            'customer_name' => $this->invoice->contact_name,
            'amount' => $this->invoice->amount,
            'invoice_at' => $this->invoice->issued_at,
            'due_at' => $this->invoice->due_at,
            'status' => $this->invoice->status,
        ];
    }

    public function getTags(): array
    {
        return [
            '{invoice_number}',
            '{invoice_total}',
            '{invoice_due_date}',
            '{invoice_status}',
            '{invoice_guest_link}',
            '{invoice_admin_link}',
            '{invoice_portal_link}',
            '{transaction_total}',
            '{transaction_paid_date}',
            '{transaction_payment_method}',
            '{customer_name}',
            '{company_name}',
            '{company_email}',
            '{company_tax_number}',
            '{company_phone}',
            '{company_address}',
        ];
    }

    public function getTagsReplacement(): array
    {
        $route_params = [
            'company_id'    => $this->invoice->company_id,
            'invoice'       => $this->invoice->id,
        ];

        return [
            $this->invoice->document_number,
            money($this->invoice->amount, $this->invoice->currency_code),
            company_date($this->invoice->due_at),
            trans('documents.statuses.' . $this->invoice->status),
            URL::signedRoute('signed.invoices.show', $route_params),
            route('invoices.show', $route_params),
            route('portal.invoices.show', $route_params),
            money($this->transaction->amount, $this->transaction->currency_code),
            company_date($this->transaction->paid_at),
            $this->transaction->payment_method,
            $this->invoice->contact_name,
            $this->invoice->company->name,
            $this->invoice->company->email,
            $this->invoice->company->tax_number,
            $this->invoice->company->phone,
            nl2br(trim($this->invoice->company->address)),
        ];
    }
}
