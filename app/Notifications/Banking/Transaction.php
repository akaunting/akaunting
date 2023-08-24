<?php

namespace App\Notifications\Banking;

use App\Abstracts\Notification;
use App\Models\Banking\Transaction as Model;
use App\Models\Setting\EmailTemplate;
use App\Traits\Transactions;
use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class Transaction extends Notification
{
    use Transactions;

    /**
     * The transaction model.
     *
     * @var object
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
    public function __construct(Model $transaction = null, string $template_alias = null, bool $attach_pdf = false, array $custom_mail = [])
    {
        parent::__construct();

        $this->transaction = $transaction;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
        $this->custom_mail = $custom_mail;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        if (! empty($this->custom_mail['to'])) {
            $notifiable->email = $this->custom_mail['to'];
        }

        $message = $this->initMailMessage();

        // Attach the PDF file
        if ($this->attach_pdf) {
            $func = is_local_storage() ? 'fromPath' : 'fromStorage';

            $path = $this->storeTransactionPdfAndGetPath($this->transaction);
            $file = Attachment::$func($path)->withMime('application/pdf');

            $message->attach($file);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $this->initArrayMessage();

        return [
            'template_alias' => $this->template->alias,
            'title' => trans('notifications.menu.' . $this->template->alias . '.title'),
            'description' => trans('notifications.menu.' . $this->template->alias . '.description', $this->getTagsBinding()),
            'transaction_id' => $this->transaction->id,
            'contact_name' => $this->transaction->contact->name,
            'amount' => $this->transaction->amount,
            'transaction_date' => company_date($this->transaction->paid_at),
        ];
    }

    public function getTags(): array
    {
        return [
            '{payment_amount}',
            '{payment_date}',
            '{payment_guest_link}',
            '{payment_admin_link}',
            '{payment_portal_link}',
            '{contact_name}',
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
            'company_id'    => $this->transaction->company_id,
            'transaction'   => $this->transaction->id,
            'payment'       => $this->transaction->id,
        ];

        return [
            money($this->transaction->amount, $this->transaction->currency_code),
            company_date($this->transaction->paid_at),
            URL::signedRoute('signed.payments.show', $route_params),
            route('transactions.show', $route_params),
            route('portal.payments.show', $route_params),
            $this->transaction->contact->name,
            $this->transaction->company->name,
            $this->transaction->company->email,
            $this->transaction->company->tax_number,
            $this->transaction->company->phone,
            nl2br(trim($this->transaction->company->address)),
        ];
    }
}
