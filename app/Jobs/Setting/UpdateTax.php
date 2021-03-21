<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Models\Setting\Tax;

class UpdateTax extends Job
{
    protected $tax;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $tax
     * @param  $request
     */
    public function __construct($tax, $request)
    {
        $this->tax = $tax;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Tax
     */
    public function handle()
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->tax->update($this->request->all());
        });

        return $this->tax;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if (!$relationships = $this->getRelationships()) {
            return;
        }

        if ($this->request->has('type') && ($this->request->get('type') != $this->tax->type)) {
            $message = trans('messages.error.change_type', ['text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }

        if (!$this->request->get('enabled')) {
            $message = trans('messages.warning.disabled', ['name' => $this->tax->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ];

        return $this->countRelationships($this->tax, $rels);
    }
}
