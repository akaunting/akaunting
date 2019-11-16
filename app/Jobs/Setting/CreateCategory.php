<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Models\Setting\Category;

class CreateCategory extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Category
     */
    public function handle()
    {
        $category = Category::create($this->request->all());

        return $category;
    }
}
