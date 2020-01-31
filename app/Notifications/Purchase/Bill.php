<?php

namespace App\Notifications\Purchase;

use App\Abstracts\Notification;

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
     * @param  object  $template
     */
    public function __construct($bill = null, $template = null)
    {
        parent::__construct();

        $this->bill = $bill;
        $this->template = $template;
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
            '{bill_due_date}',
            '{bill_admin_link}',
            '{vendor_name}',
            '{company_name}',
        ];
    }

    public function getTagsReplacement()
    {
        return [
            $this->bill->bill_number,
            money($this->bill->amount, $this->bill->currency_code, true),
            company_date($this->bill->due_at),
            route('bills.show', $this->bill->id),
            $this->bill->contact_name,
            $this->bill->company->name
        ];
    }
}
