<?php

namespace App\Notifications\Purchase;

use App\Abstracts\Notification;
use App\Models\Setting\EmailTemplate;
use App\Models\Document\Document;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class Bill extends Notification
{
    /**
     * The bill model.
     *
     * @var Document
     */
    public $bill;

    /**
     * The email template.
     *
     * @var EmailTemplate
     */
    public $template;

    /**
     * Create a notification instance.
     */
    public function __construct(Document $bill = null, string $template_alias = null)
    {
        parent::__construct();

        $this->bill = $bill;
        $this->template = EmailTemplate::alias($template_alias)->first();
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $message = $this->initMailMessage();

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
            'bill_id' => $this->bill->id,
            'bill_number' => $this->bill->document_number,
            'vendor_name' => $this->bill->contact_name,
            'amount' => $this->bill->amount,
            'billed_date' => company_date($this->bill->issued_at),
            'bill_due_date' => company_date($this->bill->due_at),
            'status' => $this->bill->status,
        ];
    }

    public function getTags(): array
    {
        return [
            '{bill_number}',
            '{bill_total}',
            '{bill_amount_due}',
            '{billed_date}',
            '{bill_due_date}',
            '{bill_admin_link}',
            '{vendor_name}',
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
            'company_id'    => $this->bill->company_id,
            'bill'          => $this->bill->id,
        ];

        return [
            $this->bill->document_number,
            money($this->bill->amount, $this->bill->currency_code),
            money($this->bill->amount_due, $this->bill->currency_code),
            company_date($this->bill->issued_at),
            company_date($this->bill->due_at),
            route('bills.show', $route_params),
            $this->bill->contact_name,
            $this->bill->company->name,
            $this->bill->company->email,
            $this->bill->company->tax_number,
            $this->bill->company->phone,
            nl2br(trim($this->bill->company->address)),
        ];
    }
}
