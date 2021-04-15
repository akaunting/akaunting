<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;
use App\Models\Module\Module;
use App\Traits\Modules;

class My extends Controller
{
    use Modules;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $purchased = $this->getMyModules();
        $modules = $this->getInstalledModules();
        $installed = Module::where('company_id', '=', company_id())->pluck('enabled', 'alias')->toArray();

        return $this->response('modules.my.index', compact('purchased', 'modules', 'installed'));
    }
}
