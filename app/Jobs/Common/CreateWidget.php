<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Widget;

class CreateWidget extends Job implements HasOwner, ShouldCreate
{
    public function handle(): Widget
    {
        \DB::transaction(function () {
            $this->widget = Widget::create($this->request->all());
        });

        return $this->widget;
    }
}
