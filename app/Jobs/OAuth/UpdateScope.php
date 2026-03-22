<?php

namespace App\Jobs\OAuth;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldUpdate;

class UpdateScope extends Job implements HasOwner, HasSource, ShouldUpdate
{
    public function handle()
    {
        \DB::transaction(function () {
            $this->model->update([
                'key'         => $this->request->get('key'),
                'name'        => $this->request->get('name'),
                'description' => $this->request->get('description', ''),
                'group'       => $this->request->get('group', 'custom'),
                'enabled'     => $this->request->get('enabled', $this->model->enabled),
                'is_default'  => $this->request->get('is_default', $this->model->is_default),
                'sort_order'  => $this->request->get('sort_order', $this->model->sort_order),
            ]);
        });

        return $this->model;
    }
}
