<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Traits\Modules;
use App\Models\Module\Module;
use Illuminate\Routing\Route;

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
        $this->checkApiToken();

        $purchased = $this->getMyModules();
        $modules = $this->getInstalledModules();
        $installed = Module::where('company_id', '=', session('company_id'))->pluck('status', 'alias')->toArray();

        return view('modules.my.index', compact('purchased', 'modules', 'installed'));
    }
}
