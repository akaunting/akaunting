<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Category as Request;
use App\Jobs\Setting\CreateCategory;
use App\Models\Setting\Category;
use App\Traits\Categories as Helper;
use App\Traits\Modules;
use Illuminate\Http\Request as IRequest;

class Categories extends Controller
{
    use Helper, Modules;

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
        $type = $request->get('type', Category::ITEM_TYPE);

        switch ($type) {
            case Category::INCOME_TYPE:
                $types = $this->getIncomeCategoryTypes();
                break;
            case Category::EXPENSE_TYPE:
                $types = $this->getExpenseCategoryTypes();
                break;
            case Category::ITEM_TYPE:
                $types = $this->getItemCategoryTypes();
                break;
            case Category::OTHER_TYPE:
                $types = $this->getOtherCategoryTypes();
                break;
            default:
                $types = [$type];
        }

        $categories = collect();

        Category::type($types)
            ->enabled()
            ->orderBy('name')
            ->get()
            ->each(function ($category) use (&$categories) {
                $categories->push([
                    'id' => $category->id,
                    'title' => $category->name,
                    'level' => $category->level,
                ]);
            });

        $has_code = $this->moduleIsEnabled('double-entry');

        $html = view('modals.categories.create', compact('type', 'types', 'categories', 'has_code'))->render();

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
        $request['type'] = $request->get('type', Category::ITEM_TYPE);
        $request['color'] = $request->get('color', '#' . dechex(rand(0x000000, 0xFFFFFF)));

        $response = $this->ajaxDispatch(new CreateCategory($request));

        if ($response['success']) {
            $response['message'] = trans('messages.success.created', ['type' => trans_choice('general.categories', 1)]);
        }

        return response()->json($response);
    }
}
