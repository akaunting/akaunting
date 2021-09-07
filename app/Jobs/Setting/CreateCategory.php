<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Setting\Category;

class CreateCategory extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Category
    {
        \DB::transaction(function () {
            $this->model = Category::create($this->request->all());
        });

        return $this->model;
    }
}
