<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use App\Observers\Transaction;
use Illuminate\Support\Str;

class DeleteDocument extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        \DB::transaction(function () {
            Transaction::mute();

            $this->deleteRelationships($this->model, [
                'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
            ]);

            $this->model->delete();

            Transaction::unmute();
        });

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if ($this->model->transactions()->isReconciled()->count()) {
            $type = Str::plural($this->model->type);
            $message = trans('messages.warning.reconciled_doc', ['type' => trans_choice("general.$type", 1)]);

            throw new \Exception($message);
        }
    }
}
