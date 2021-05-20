<?php

namespace App\Http\Controllers\Wizard;

use App\Abstracts\Http\Controller;
use App\Traits\Modules;

class Finish extends Controller
{
    use Modules;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:read-admin-panel')->only('index', 'show', 'edit', 'export');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        setting()->set('wizard.completed', 1);

        // Save all settings
        setting()->save();

        $data = [
            'query' => [
                'limit' => 4
            ]
        ];

        $modules = $this->getFeaturedModules($data);

        return $this->response('wizard.finish.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update()
    {
        setting()->set('wizard.completed', 1);

        // Save all settings
        setting()->save();

        return response()->json([]);
    }
}
