<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionSending;
use App\Events\Banking\TransactionSent;
use App\Http\Requests\Common\CustomMail as Request;
use App\Models\Banking\Transaction;
use App\Notifications\Banking\Transaction as Notification;

class SendTransactionAsCustomMail extends Job
{
    public string $template_alias;

    public function __construct(Request $request, string $template_alias)
    {
        $this->request = $request;
        $this->template_alias = $template_alias;
    }

    public function handle(): void
    {
        $transaction = Transaction::find($this->request->get('transaction_id'));

        event(new TransactionSending($transaction));

        $mail_request = $this->request->only(['to', 'subject', 'body']);

        if ($this->request->get('user_email', false)) {
            $mail_request['bcc'] = user()->email;
        }

        $attachments = collect($this->request->get('attachments', []))
            ->filter(fn($value) => $value == true)
            ->keys()
            ->all();

        $attach_pdf = in_array('pdf', $attachments);

        $contacts = $transaction->contact->withPersons();

        $counter = 1;

        foreach ($contacts as $contact) {
            if (! in_array($contact->email, $mail_request['to'])) {
                continue;
            }

            $custom_mail = [
                'subject'   => $mail_request['subject'],
                'body'      => $mail_request['body'],
            ];

            if (($counter == 1) && ! empty($mail_request['bcc'])) {
                $custom_mail['bcc'] = $mail_request['bcc'];
            }

            $contact->notify(new Notification($transaction, $this->template_alias, $attach_pdf, $custom_mail, $attachments));

            $counter++;
        }

        event(new TransactionSent($transaction));
    }
}
