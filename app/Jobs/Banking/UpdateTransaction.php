<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionUpdated;
use App\Events\Banking\TransactionUpdating;
use App\Exceptions\Job\NotAllowed;
use App\Interfaces\Job\ShouldUpdate;
use App\Jobs\Banking\CreateTransactionTaxes;
use App\Models\Banking\Transaction;

class UpdateTransaction extends Job implements ShouldUpdate
{
    public function handle(): Transaction
    {
        $this->authorize();

        // A request without a type, such as ending a recurring template, keeps the one it has
        // rather than turning an expense into an income
        if (! $this->request->has('type')) {
            $this->request->merge(['type' => $this->model->type]);
        }

        event(new TransactionUpdating($this->model, $this->request));

        if (! array_key_exists($this->request->get('type'), config('type.transaction'))) {
            $isExpense = str_contains((string) $this->request->get('type', ''), 'expense');
            $isRecurring = ! empty($this->request->get('recurring_frequency')) && $this->request->get('recurring_frequency') !== 'no';

            $type = $isExpense
                ? ($isRecurring ? Transaction::EXPENSE_RECURRING_TYPE : Transaction::EXPENSE_TYPE)
                : ($isRecurring ? Transaction::INCOME_RECURRING_TYPE : Transaction::INCOME_TYPE);

            $this->request->merge(['type' => $type]);
        }

        \DB::transaction(function () {
            $this->model->update($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'transactions');

                    $this->model->attachMedia($media, 'attachment');
                }
            } elseif ($this->isWholeRecord('amount') && $this->request->isNotApi() && ! $this->request->file('attachment') && $this->model->attachment) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);
            } elseif ($this->request->isApi() && $this->request->has('remove_attachment') && $this->model->attachment) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);
            }

            // The taxes are worked out from the amount, which only the whole record sends; matching
            // to a document, ending a recurring template or a bulk edit keeps them
            if ($this->isWholeRecord('amount')) {
                $this->deleteRelationships($this->model, ['taxes'], true);

                $this->dispatch(new CreateTransactionTaxes($this->model, $this->request));
            }

            // Recurring
            $this->model->updateRecurring($this->request->all());
        });

        event(new TransactionUpdated($this->model, $this->request));

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if ($this->model->reconciled) {
            $message = trans('messages.warning.reconciled_tran');

            throw new NotAllowed($message);
        }

        if ($this->model->isTransferTransaction()) {
            throw new NotAllowed(trans('messages.error.transfer_transaction'));
        }
    }
}
