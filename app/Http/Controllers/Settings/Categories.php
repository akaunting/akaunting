<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Category as Request;
use App\Models\Setting\Category;

class Categories extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::collect();

        $types = collect(['expense' => 'Expense', 'income' => 'Income', 'item' => 'Item', 'other' => 'Other'])
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.types', 2)]), '');

        return view('settings.categories.index', compact('categories', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('settings.categories.create');
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
        Category::create($request->all());

        $message = trans('messages.success.added', ['type' => trans_choice('general.categories', 1)]);

        flash($message)->success();

        return redirect('settings/categories');
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
        return view('settings.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Category  $category
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Category $category, Request $request)
    {
        $category->update($request->all());

        $message = trans('messages.success.updated', ['type' => trans_choice('general.categories', 1)]);

        flash($message)->success();

        return redirect('settings/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     *
     * @return Response
     */
    public function destroy(Category $category)
    {
        $canDelete = $category->canDelete();

        if ($canDelete === true) {
            $category->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.categories', 1)]);

            flash($message)->success();
        } else {
            $text = array();

            if (isset($canDelete['items'])) {
                $text[] = '<b>' . $canDelete['items'] . '</b> ' . trans_choice('general.items', ($canDelete['items'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['payments'])) {
                $text[] = '<b>' . $canDelete['payments'] . '</b> ' . trans_choice('general.payments', ($canDelete['payments'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['revenues'])) {
                $text[] = '<b>' . $canDelete['revenues'] . '</b> ' . trans_choice('general.items', ($canDelete['revenues'] > 1) ? 2 : 1);
            }

            $message = trans('messages.warning.deleted', ['type' => trans_choice('general.categories', 1), 'text' => implode(', ', $text)]);

            flash($message)->warning();
        }

        return redirect('settings/categories');
    }
}
