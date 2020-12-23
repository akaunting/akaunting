<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Observers\Transaction;
use Illuminate\Support\Str;

class DeleteDocument extends Job
{
    protected $document;

    /**
     * Create a new job instance.
     *
     * @param  $document
     */
    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        \DB::transaction(function () {
            Transaction::mute();

            $this->deleteRelationships($this->document, [
                'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
            ]);

            $this->document->delete();

            Transaction::unmute();
        });

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($this->document->transactions()->isReconciled()->count()) {
            $type = Str::plural($this->document->type);
            $message = trans('messages.warning.reconciled_doc', ['type' => trans_choice("general.$type", 1)]);

            throw new \Exception($message);
        }
    }
}
