<?php

namespace App\Notifications\Sale;

use App\Abstracts\Notification;
use App\Models\Common\EmailTemplate;
use App\Traits\Transactions;
use Illuminate\Support\Facades\URL;

class Revenue extends Notification
{
    use Transactions;

    /**
     * The revenue model.
     *
     * @var object
     */
    public $revenue;

    /**
     * The email template.
     *
     * @var \App\Models\Common\EmailTemplate
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
     * @param  object  $revenue
     * @param  object  $template_alias
     * @param  object  $attach_pdf
     */
    public function __construct($revenue = null, $template_alias = null, $attach_pdf = false)
    {
        parent::__construct();

        $this->revenue = $revenue;
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
            $message->attach($this->storeTransactionPdfAndGetPath($this->revenue), [
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
            'revenue_id' => $this->revenue->id,
            'customer_name' => $this->revenue->contact->name,
            'amount' => $this->revenue->amount,
            'revenue_date' => company_date($this->revenue->paid_at),
        ];
    }

    public function getTags()
    {
        return [
            '{revenue_amount}',
            '{revenue_date}',
            '{revenue_guest_link}',
            '{revenue_admin_link}',
            '{revenue_portal_link}',
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
            money($this->revenue->amount, $this->revenue->currency_code, true),
            company_date($this->revenue->paid_at),
            URL::signedRoute('signed.payments.show', [$this->revenue->id]),
            route('revenues.show', $this->revenue->id),
            route('portal.payments.show', $this->revenue->id),
            $this->revenue->contact->name,
            $this->revenue->company->name,
            $this->revenue->company->email,
            $this->revenue->company->tax_number,
            $this->revenue->company->phone,
            nl2br(trim($this->revenue->company->address)),
        ];
    }
}
