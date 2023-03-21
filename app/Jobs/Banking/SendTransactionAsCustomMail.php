<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
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

        $custom_mail = $this->request->only(['to', 'subject', 'body']);

        if ($this->request->get('user_email', false)) {
            $custom_mail['cc'] = user()->email;
        }

        // Notify the contact
        $transaction->contact->notify(new Notification($transaction, $this->template_alias, true, $custom_mail));

        event(new TransactionSent($transaction));
    }
}
