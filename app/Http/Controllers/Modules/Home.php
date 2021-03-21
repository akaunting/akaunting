<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;
use App\Traits\Modules;
use App\Models\Module\Module;

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
        $data = [
            'query' => [
                'limit' => 4
            ]
        ];

        $pre_sale = $this->getPreSaleModules($data);
        $paid = $this->getPaidModules($data);
        $new = $this->getNewModules($data);
        $free = $this->getFreeModules($data);
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return $this->response('modules.home.index', compact('pre_sale', 'paid', 'new', 'free', 'installed'));
    }
}
