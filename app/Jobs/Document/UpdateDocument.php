<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\PaidAmountCalculated;
use App\Events\Document\DocumentUpdated;
use App\Events\Document\DocumentUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Jobs\Document\CreateDocumentItemsAndTotals;
use App\Models\Document\Document;
use App\Traits\Relationships;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UpdateDocument extends Job implements ShouldUpdate
{
    use Relationships;

    public function handle(): Document
    {
        $this->authorize();

        // An absent items key is a partial update; rebuilding from it would force delete the lines
        $has_items = $this->request->has('items');

        // Derived from the lines, never taken from the caller
        $this->request['amount'] = $has_items ? 0 : $this->model->amount;

        // Disable this lines for global discount issue fixed ( https://github.com/akaunting/akaunting/issues/2797 )
        if ($this->request->has('discount')) {
            $this->request['discount_rate'] = $this->request['discount'];
        }

        event(new DocumentUpdating($this->model, $this->request));

        // Track original contact_id to sync transactions if it changes
        $originalContactId = $this->model->contact_id;

        \DB::transaction(function () use ($originalContactId, $has_items) {
            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, Str::plural($this->model->type));

                    $this->model->attachMedia($media, 'attachment');
                }
            } elseif ($this->request->isNotApi() && ! $this->request->file('attachment') && $this->model->attachment) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);
            } elseif ($this->request->isApi() && $this->request->has('remove_attachment') && $this->model->attachment) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);
            }

            if ($has_items) {
                $this->deleteRelationships($this->model, ['items', 'item_taxes', 'totals'], true);

                $this->dispatch(new CreateDocumentItemsAndTotals($this->model, $this->request));
            }

            $this->model->paid_amount = $this->model->paid;

            event(new PaidAmountCalculated($this->model));

            if ($this->model->paid_amount > 0) {
                // Rounded before comparing, otherwise a float cent keeps a settled document partial
                $currency_code = $this->request['currency_code'] ?? $this->model->currency_code;
                $precision = currency($currency_code)->getPrecision();

                $amount = round((float) $this->request['amount'], $precision);
                $paid = round((float) $this->model->paid_amount, $precision);

                // At or below what was already paid counts as settled, including an overpayment
                $this->request['status'] = ($amount > $paid) ? 'partial' : 'paid';
            }

            unset($this->model->reconciled);
            unset($this->model->paid_amount);

            $this->model->update($this->getUpdatePayload());

            // Sync transaction contact_id if document contact changed (skip reconciled transactions)
            if (isset($this->request['contact_id']) && $originalContactId != $this->request['contact_id']) {
                $this->model->transactions()->where('reconciled', 0)->update([
                    'contact_id' => $this->request['contact_id'],
                ]);
            }

            $this->model->updateRecurring($this->request->all());
        });

        event(new DocumentUpdated($this->model, $this->request));

        return $this->model;
    }

    /**
     * The attributes an update may write, without the ones that identify the document.
     */
    protected function getUpdatePayload(): array
    {
        return Arr::except($this->request->all(), ['company_id', 'type', 'parent_id']);
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if (
            isset($this->request['contact_id']) &&
            (int) $this->request['contact_id'] !== (int) $this->model->contact_id
        ) {
            $lockedStatuses = ['sent', 'received', 'viewed', 'partial', 'paid', 'overdue', 'unpaid', 'cancelled'];

            if (in_array($this->model->status, $lockedStatuses)) {
                $type = Str::plural($this->model->type);
                $message = trans('messages.warning.contact_change', ['type' => trans_choice("general.$type", 1)]);

                throw new \Exception($message);
            } else if ($this->model->transactions()->isReconciled()->exists()) {
                $type = Str::plural($this->model->type);
                $message = trans('messages.warning.reconciled_doc', ['type' => trans_choice("general.$type", 1)]);

                throw new \Exception($message);
            }
        }
    }
}
