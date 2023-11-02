<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Traits\Plans as PlansTrait;

class Plans extends Controller
{
    use PlansTrait;

    /**
     * Check for plan changes.
     *
     * @return Response
     */
    public function check()
    {
        $this->clearPlansCache();

        return redirect()->back();
    }
}
