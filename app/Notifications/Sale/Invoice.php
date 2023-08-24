<?php

namespace App\Notifications\Sale;

use App\Abstracts\Notification;
use App\Models\Setting\EmailTemplate;
use App\Models\Document\Document;
use App\Traits\Documents;
use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
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
     * List of document attachments to attach when sending the email.
     *
     * @var array
     */
    public $attachments;

    /**
     * Create a notification instance.
     */
    public function __construct(Document $invoice = null, string $template_alias = null, bool $attach_pdf = false, array $custom_mail = [], $attachments = [])
    {
        parent::__construct();

        $this->invoice = $invoice;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
        $this->custom_mail = $custom_mail;
        $this->attachments = $attachments;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        if (!empty($this->custom_mail['to'])) {
            $notifiable->email = $this->custom_mail['to'];
        }

        $message = $this->initMailMessage();

        $func = is_local_storage() ? 'fromPath' : 'fromStorage';

        // Attach the PDF file
        if ($this->attach_pdf) {
            $path = $this->storeDocumentPdfAndGetPath($this->invoice);
            $file = Attachment::$func($path)->withMime('application/pdf');

            $message->attach($file);
        }

        // Attach selected attachments
        if (! empty($this->invoice->attachment)) {
            foreach ($this->invoice->attachment as $attachment) {
                if (! in_array($attachment->id, $this->attachments)) {
                    continue;
                }

                $path = is_local_storage() ? $attachment->getAbsolutePath() : $attachment->getDiskPath();
                $file = Attachment::$func($path)->withMime($attachment->mime_type);

                $message->attach($file);
            }
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
            'invoiced_date' => company_date($this->invoice->issued_at),
            'invoice_due_date' => company_date($this->invoice->due_at),
            'status' => $this->invoice->status,
        ];
    }

    public function getTags(): array
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

    public function getTagsReplacement(): array
    {
        $route_params = [
            'company_id'    => $this->invoice->company_id,
            'invoice'       => $this->invoice->id,
        ];

        return [
            $this->invoice->document_number,
            money($this->invoice->amount, $this->invoice->currency_code),
            money($this->invoice->amount_due, $this->invoice->currency_code),
            company_date($this->invoice->issued_at),
            company_date($this->invoice->due_at),
            URL::signedRoute('signed.invoices.show', $route_params),
            route('invoices.show', $route_params),
            route('portal.invoices.show', $route_params),
            $this->invoice->contact_name,
            $this->invoice->company->name,
            $this->invoice->company->email,
            $this->invoice->company->tax_number,
            $this->invoice->company->phone,
            nl2br(trim($this->invoice->company->address)),
        ];
    }
}
