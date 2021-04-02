<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Models\Setting\Category;

class DeleteCategory extends Job
{
    protected $category;

    /**
     * Create a new job instance.
     *
     * @param  $category
     */
    public function __construct($category)
    {
        $this->category = $category;
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
            $this->category->delete();
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
        // Can not delete the last category by type
        if (Category::where('type', $this->category->type)->count() == 1) {
            $message = trans('messages.error.last_category', ['type' => strtolower(trans_choice('general.' . $this->category->type . 's', 1))]);

            throw new \Exception($message);
        }

        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->category->name, 'text' => implode(', ', $relationships)]);

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
