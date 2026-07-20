<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Traits\Plans as PlansTrait;

class Plans extends Controller
{
    use PlansTrait;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:read-admin-panel')->only('check');
    }

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
