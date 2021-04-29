<?php

namespace App\Notifications\Purchase;

use App\Abstracts\Notification;
use App\Models\Common\EmailTemplate;

class Bill extends Notification
{
    /**
     * The bill model.
     *
     * @var object
     */
    public $bill;

    /**
     * The email template.
     *
     * @var string
     */
    public $template;

    /**
     * Create a notification instance.
     *
     * @param  object  $bill
     * @param  object  $template_alias
     */
    public function __construct($bill = null, $template_alias = null)
    {
        parent::__construct();

        $this->bill = $bill;
        $this->template = EmailTemplate::alias($template_alias)->first();
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = $this->initMessage();

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

    public function getTags()
    {
        return [
            '{bill_number}',
            '{bill_total}',
            '{bill_amount_due}',
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

    public function getTagsReplacement()
    {
        return [
            $this->bill->document_number,
            money($this->bill->amount, $this->bill->currency_code, true),
            money($this->bill->amount_due, $this->bill->currency_code, true),
            company_date($this->bill->due_at),
            route('bills.show', $this->bill->id),
            $this->bill->contact_name,
            $this->bill->company->name,
            $this->bill->company->email,
            $this->bill->company->tax_number,
            $this->bill->company->phone,
            nl2br(trim($this->bill->company->address)),
        ];
    }
}
