<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionUpdated;
use App\Events\Banking\TransactionUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Jobs\Banking\CreateTransactionTaxes;
use App\Models\Banking\Transaction;

class UpdateTransaction extends Job implements ShouldUpdate
{
    public function handle(): Transaction
    {
        $this->authorize();

        event(new TransactionUpdating($this->model, $this->request));

        if (! array_key_exists($this->request->get('type'), config('type.transaction'))) {
            $type = (empty($this->request->get('recurring_frequency')) || ($this->request->get('recurring_frequency') == 'no')) ? Transaction::INCOME_TYPE : Transaction::INCOME_RECURRING_TYPE;

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
            } elseif (! $this->request->file('attachment') && $this->model->attachment) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);
            }

            $this->deleteRelationships($this->model, ['taxes'], true);

            $this->dispatch(new CreateTransactionTaxes($this->model, $this->request));

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

            throw new \Exception($message);
        }

        if ($this->model->isTransferTransaction()) {
            throw new \Exception('Unauthorized');
        }
    }
}
