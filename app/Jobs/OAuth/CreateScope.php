<?php

namespace App\Jobs\OAuth;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\OAuth\Scope;

class CreateScope extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle()
    {
        \DB::transaction(function () {
            $this->model = Scope::create([
                'key'         => $this->request->get('key'),
                'name'        => $this->request->get('name'),
                'description' => $this->request->get('description', ''),
                'group'       => $this->request->get('group', 'custom'),
                'enabled'     => $this->request->get('enabled', true),
                'is_default'  => $this->request->get('is_default', false),
                'sort_order'  => $this->request->get('sort_order', 0),
            ]);
        });

        return $this->model;
    }
}
