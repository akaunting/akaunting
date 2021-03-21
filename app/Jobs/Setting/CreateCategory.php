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
