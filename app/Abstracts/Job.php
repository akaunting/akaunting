<?php

namespace App\Abstracts;

use App\Abstracts\Http\FormRequest;
use App\Traits\Jobs;
use App\Traits\Relationships;
use App\Traits\Uploads;

abstract class Job
{
    use Jobs, Relationships, Uploads;

    public function getRequestInstance($request)
    {
        if (!is_array($request)) {
            return $request;
        }

        $class = new class() extends FormRequest {};

        return $class->merge($request);
    }
}
