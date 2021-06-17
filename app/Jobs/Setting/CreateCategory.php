<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Models\Setting\Category;

class CreateCategory extends Job
{
    protected $category;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Category
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->category = Category::create($this->request->all());
        });

        return $this->category;
    }
}
