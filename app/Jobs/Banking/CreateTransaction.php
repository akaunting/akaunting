<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionCreated;
use App\Events\Banking\TransactionCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Banking\CreateTransactionTaxes;
use App\Models\Banking\Transaction;

class CreateTransaction extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Transaction
    {
        event(new TransactionCreating($this->request));

        if (! array_key_exists($this->request->get('type'), config('type.transaction'))) {
            $isExpense = str_contains((string) $this->request->get('type', ''), 'expense');
            $isRecurring = ! empty($this->request->get('recurring_frequency')) && $this->request->get('recurring_frequency') !== 'no';

            $type = $isExpense
                ? ($isRecurring ? Transaction::EXPENSE_RECURRING_TYPE : Transaction::EXPENSE_TYPE)
                : ($isRecurring ? Transaction::INCOME_RECURRING_TYPE : Transaction::INCOME_TYPE);

            $this->request->merge(['type' => $type]);
        }

        \DB::transaction(function () {
            $this->model = Transaction::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'transactions');

                    $this->model->attachMedia($media, 'attachment');
                }
            }

            $this->dispatch(new CreateTransactionTaxes($this->model, $this->request));

            // Recurring
            $this->model->createRecurring($this->request->all());
        });

        event(new TransactionCreated($this->model));

        return $this->model;
    }
}
