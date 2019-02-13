<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Category as Request;
use Illuminate\Http\Request as CRequest;
use App\Models\Setting\Category;

class Categories extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-settings-categories')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-settings-categories')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-settings-categories')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-settings-categories')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(CRequest $request)
    {
        $type = $request['type'];

        $category_selector = false;

        if (request()->has('category_selector')) {
            $category_selector = request()->get('category_selector');
        }

        $rand = rand();

        $html = view('modals.categories.create', compact('currencies', 'type', 'category_selector', 'rand'))->render();

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

        $category = Category::create($request->all());

        $message = trans('messages.success.added', ['type' => trans_choice('general.categories', 1)]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $category,
            'message' => $message,
            'html' => 'null',
        ]);
    }
}
