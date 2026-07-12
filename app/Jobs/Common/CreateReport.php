<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Report;

class CreateReport extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Report
    {
        // Security: strip HTML tags from description to prevent stored XSS.
        // The textarea and index views now use {{ }} encoding, but this
        // defense-in-depth ensures no raw HTML is persisted.
        if ($this->request->has('description')) {
            $this->request->merge([
                'description' => strip_tags($this->request->get('description')),
            ]);
        }

        \DB::transaction(function () {
            $this->model = Report::create($this->request->all());
        });

        return $this->model;
    }
}
