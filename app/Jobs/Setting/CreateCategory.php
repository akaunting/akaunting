<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Events\Setting\CategoryCreated;
use App\Events\Setting\CategoryCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Setting\Category;

class CreateCategory extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Category
    {
        event(new CategoryCreating($this->request));

        \DB::transaction(function () {
            $this->model = Category::create($this->request->all());
        });

        event(new CategoryCreated($this->model, $this->request));

        return $this->model;
    }
}
