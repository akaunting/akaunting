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

        $transfer_id = Category::transfer();

        $types = collect(['expense' => 'Expense', 'income' => 'Income', 'item' => 'Item', 'other' => 'Other'])
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.types', 2)]), '');

        return view('settings.categories.index', compact('categories', 'types', 'transfer_id'));
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
        $relationships = $this->countRelationships($category, [
            'items' => 'items',
            'revenues' => 'revenues',
            'payments' => 'payments',
        ]);

        if (empty($relationships) || $request['enabled']) {
            $category->update($request->all());

            $message = trans('messages.success.updated', ['type' => trans_choice('general.categories', 1)]);

            flash($message)->success();

            return redirect('settings/categories');
        } else {
            $message = trans('messages.warning.disabled', ['name' => $category->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();

            return redirect('settings/categories/' . $category->id . '/edit');
        }
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
        $relationships = $this->countRelationships($category, [
            'items' => 'items',
            'revenues' => 'revenues',
            'payments' => 'payments',
        ]);

        // Can't delete transfer category
        if ($category->id == Category::transfer()) {
            return redirect('settings/categories');
        }

        if (empty($relationships)) {
            $category->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.categories', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.deleted', ['name' => $category->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect('settings/categories');
    }
}
