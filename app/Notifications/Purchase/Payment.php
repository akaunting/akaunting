<?php

namespace App\Notifications\Purchase;

use App\Abstracts\Notification;
use App\Models\Common\EmailTemplate;
use App\Traits\Transactions;
use Illuminate\Support\Facades\URL;

class Payment extends Notification
{
    use Transactions;

    /**
     * The payment model.
     *
     * @var object
     */
    public $payment;

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
     * @param  object  $payment
     * @param  object  $template_alias
     * @param  object  $attach_pdf
     */
    public function __construct($payment = null, $template_alias = null, $attach_pdf = false)
    {
        parent::__construct();

        $this->payment = $payment;
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
            $message->attach($this->storeTransactionPdfAndGetPath($this->payment), [
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
            'payment_id' => $this->payment->id,
            'vendor_name' => $this->payment->contact->name,
            'amount' => $this->payment->amount,
            'payment_date' => company_date($this->payment->paid_at),
        ];
    }

    public function getTags()
    {
        return [
            '{payment_amount}',
            '{payment_date}',
            '{payment_admin_link}',
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
            money($this->payment->amount, $this->payment->currency_code, true),
            company_date($this->payment->paid_at),
            route('payments.show', $this->payment->id),
            $this->payment->contact->name,
            $this->payment->company->name,
            $this->payment->company->email,
            $this->payment->company->tax_number,
            $this->payment->company->phone,
            nl2br(trim($this->payment->company->address)),
        ];
    }
}
