<?php

namespace App\Http\Controllers\Wizard;

use Illuminate\Routing\Controller;
use App\Traits\Modules;
use App\Models\Module\Module;

class Finish extends Controller
{
    use Modules;

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        setting()->set('general.wizard', true);

        // Save all settings
        setting()->save();

        $data = [
            'query' => [
                'limit' => 4
            ]
        ];

        $modules = $this->getFeaturedModules($data);

        return view('wizard.finish.index', compact('modules'));
    }
}
