<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Traits\Modules;
use App\Models\Module\Module;
use Illuminate\Routing\Route;

class Home extends Controller
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

        $data = [
            'query' => [
                'limit' => 4
            ]
        ];

        $pre_sale = $this->getPreSaleModules($data);
        $paid = $this->getPaidModules($data);
        $new = $this->getNewModules($data);
        $free = $this->getFreeModules($data);
        $installed = Module::all()->pluck('status', 'alias')->toArray();

        return view('modules.home.index', compact('pre_sale', 'paid', 'new', 'free', 'installed'));
    }
}
