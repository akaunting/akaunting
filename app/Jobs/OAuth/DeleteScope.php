<?php

namespace App\Jobs\OAuth;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;

class DeleteScope extends Job implements ShouldDelete
{
    public function handle()
    {
        \DB::transaction(function () {
            $this->model->delete();
        });

        return $this->model;
    }
}
