<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Jobs\Common\CreateItem;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use Illuminate\Http\Request as IRequest;

class Items extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-common-items')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-common-items')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-common-items')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-common-items')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(IRequest $request)
    {
        $categories = Category::item()->enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $currency = Currency::where('code', setting('default.currency', 'USD'))->first();

        $html = view('modals.items.create', compact('categories', 'taxes', 'currency'))->render();

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
     * @param  $request
     * @return Response
     */
    public function store(IRequest $request)
    {
        if ($request->get('type', false) == 'inline') {
            $data = [
                'company_id' => session('company_id'),
                'name' => '',
                'sale_price' => 0,
                'purchase_price' => 0,
                'enabled' => 1,
            ];

            $data[$request->get('field')] = $request->get('value');

            $request = $data;
        }

        $response = $this->ajaxDispatch(new CreateItem($request));

        if ($response['success']) {
            $response['message'] = trans('messages.success.added', ['type' => trans_choice('general.items', 1)]);
        }

        return response()->json($response);
    }
}
