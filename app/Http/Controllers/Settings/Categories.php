<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Exports\Settings\Categories as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Setting\Category as Request;
use App\Imports\Settings\Categories as Import;
use App\Jobs\Setting\CreateCategory;
use App\Jobs\Setting\DeleteCategory;
use App\Jobs\Setting\UpdateCategory;
use App\Models\Setting\Category;
use App\Traits\Categories as Helper;
use App\Traits\Modules;

class Categories extends Controller
{
    use Helper, Modules;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->setActiveTabForCategories();

        $query = Category::with('sub_categories');

        if (search_string_value('searchable')) {
            $query->withSubCategory();
        }

        $types = $this->getCategoryTypes();

        if (request()->get('list_records') == 'all') {
            $query->type(array_keys($types));
        }

        $categories = $query->collect();

        $tabs = $this->getCategoryTabs();

        $tab = request()->get('list_records');
        $tab_active = ! empty($tab) ? 'categories-' . $tab : 'categories-all';

        $hide_code_column = true;

        $search_string_type = search_string_value('type');
        $selected_types = ! empty($search_string_type) ? explode(',', $search_string_type) : array_keys($types);

        foreach (config('type.category', []) as $type => $config) {
            if (! in_array($type, $selected_types)) {
                continue;
            }

            if (empty($config['hide']) || !in_array('code', $config['hide'])) {
                $hide_code_column = false;
                break;
            }
        }

        return $this->response('settings.categories.index', compact('categories', 'types', 'tabs', 'tab_active', 'hide_code_column'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $types = $this->getCategoryTypes(true, true);

        $categories = [];

        foreach (config('type.category') as $type => $config) {
            $categories[$type] = [];
        }

        $has_code = $this->moduleIsEnabled('double-entry');

        Category::enabled()->orderBy('name')->get()->each(function ($category) use (&$categories) {
            $categories[$category->type][] = [
                'id' => $category->id,
                'title' => $category->name,
                'level' => $category->level,
            ];
        });

        return view('settings.categories.create', compact('types', 'categories', 'has_code'));
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
        $response = $this->ajaxDispatch(new CreateCategory($request));

        if ($response['success']) {
            $response['redirect'] = route('categories.index');

            $message = trans('messages.success.created', ['type' => trans_choice('general.categories', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('categories.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest  $request
     *
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('general.categories', 2));

        if ($response['success']) {
            $response['redirect'] = route('categories.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['settings', 'categories']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Category  $category
     *
     * @return Response
     */
    public function edit(Category $category)
    {
        $types = $this->getCategoryTypes(true, true);

        $type_disabled = (Category::where('type', $category->type)->count() == 1) ?: false;

        $edited_category_id = $category->id;

        $categories = [];

        foreach (config('type.category') as $type => $config) {
            $categories[$type] = [];
        }

        $skip_categories = [];
        $skip_categories[] = $edited_category_id;

        foreach ($category->sub_categories as $sub_category) {
            $skip_categories[] = $sub_category->id;

            if ($sub_category->sub_categories) {
                $skips = $this->getChildrenCategoryIds($sub_category);

                foreach ($skips as $skip) {
                    $skip_categories[] = $skip;
                }
            }
        }

        Category::enabled()->orderBy('name')->get()->each(function ($category) use (&$categories, &$skip_categories) {
            if (in_array($category->id, $skip_categories)) {
                return;
            }

            $categories[$category->type][] = [
                'id' => $category->id,
                'title' => $category->name,
                'level' => $category->level,
            ];
        });

        $parent_categories = $categories[$category->type] ?? [];

        $has_code = $this->moduleIsEnabled('double-entry');

        return view('settings.categories.edit', compact('category', 'types', 'type_disabled', 'categories', 'parent_categories', 'has_code'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Category $category
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Category $category, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateCategory($category, $request));

        if ($response['success']) {
            $response['redirect'] = route('categories.index');

            $message = trans('messages.success.updated', ['type' => $category->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('categories.edit', $category->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Category $category
     *
     * @return Response
     */
    public function enable(Category $category)
    {
        $response = $this->ajaxDispatch(new UpdateCategory($category, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $category->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Category $category
     *
     * @return Response
     */
    public function disable(Category $category)
    {
        $response = $this->ajaxDispatch(new UpdateCategory($category, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $category->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category $category
     *
     * @return Response
     */
    public function destroy(Category $category)
    {
        $response = $this->ajaxDispatch(new DeleteCategory($category));

        $response['redirect'] = route('categories.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $category->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('general.categories', 2));
    }
}
