<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Traits\Modules;
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

        $paid = $this->getPaidModules($data);
        $new = $this->getNewModules($data);
        $free = $this->getFreeModules($data);

        return view('modules.home.index', compact('paid', 'new', 'free'));
    }
}
