<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Common\Report;

class UpdateReport extends Job implements ShouldUpdate
{
    public function handle(): Report
    {
        // Security: strip HTML tags from description to prevent stored XSS.
        if ($this->request->has('description')) {
            $this->request->merge([
                'description' => strip_tags($this->request->get('description')),
            ]);
        }

        \DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        return $this->model;
    }
}
