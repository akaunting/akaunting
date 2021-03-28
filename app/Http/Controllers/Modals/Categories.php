<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Jobs\Setting\CreateCategory;
use Illuminate\Http\Request as IRequest;
use App\Http\Requests\Setting\Category as Request;

class Categories extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-settings-categories')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-settings-categories')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-categories')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-settings-categories')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(IRequest $request)
    {
        $type = $request->get('type', 'item');

        $html = view('modals.categories.create', compact('type'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request['enabled'] = 1;
        $request['type'] = $request->get('type', 'income');
        $request['color'] = $request->get('color', '#' . dechex(rand(0x000000, 0xFFFFFF)));

        $response = $this->ajaxDispatch(new CreateCategory($request));

        if ($response['success']) {
            $response['message'] = trans('messages.success.added', ['type' => trans_choice('general.categories', 1)]);
        }

        return response()->json($response);
    }
}
