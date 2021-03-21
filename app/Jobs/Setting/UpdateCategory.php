<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Models\Setting\Category;

class UpdateCategory extends Job
{
    protected $category;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $category
     * @param  $request
     */
    public function __construct($category, $request)
    {
        $this->category = $category;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Category
     */
    public function handle()
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->category->update($this->request->all());
        });

        return $this->category;
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

        if ($this->request->has('type') && ($this->request->get('type') != $this->category->type)) {
            $message = trans('messages.error.change_type', ['text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }

        if (!$this->request->get('enabled')) {
            $message = trans('messages.warning.disabled', ['name' => $this->category->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
            'items' => 'items',
            'invoices' => 'invoices',
            'bills' => 'bills',
            'transactions' => 'transactions',
        ];

        return $this->countRelationships($this->category, $rels);
    }
}
