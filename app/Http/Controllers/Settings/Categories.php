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

class Categories extends Controller
{
    use Helper;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::collect();

        $transfer_id = Category::transfer();

        $types = $this->getCategoryTypes();

        return $this->response('settings.categories.index', compact('categories', 'types', 'transfer_id'));
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
        $types = $this->getCategoryTypes();

        return view('settings.categories.create', compact('types'));
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

            $message = trans('messages.success.added', ['type' => trans_choice('general.categories', 1)]);

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
        $types = $this->getCategoryTypes();

        $type_disabled = (Category::where('type', $category->type)->count() == 1) ?: false;

        return view('settings.categories.edit', compact('category', 'types', 'type_disabled'));
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
