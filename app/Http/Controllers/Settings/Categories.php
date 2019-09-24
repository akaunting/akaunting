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

        $types = collect([
            'expense' => trans_choice('general.expenses', 1),
            'income' => trans_choice('general.incomes', 1),
            'item' => trans_choice('general.items', 1),
            'other' => trans_choice('general.others', 1),
        ]);

        return view('settings.categories.index', compact('categories', 'types', 'transfer_id'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect('settings/categories');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $types = [
            'expense' => trans_choice('general.expenses', 1),
            'income' => trans_choice('general.incomes', 1),
            'item' => trans_choice('general.items', 1),
            'other' => trans_choice('general.others', 1),
        ];

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
        $types = [
            'expense' => trans_choice('general.expenses', 1),
            'income' => trans_choice('general.incomes', 1),
            'item' => trans_choice('general.items', 1),
            'other' => trans_choice('general.others', 1),
        ];

        $type_disabled = (Category::where('type', $category->type)->count() == 1) ?: false;

        return view('settings.categories.edit', compact('category', 'types', 'type_disabled'));
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
            'invoices' => 'invoices',
            'revenues' => 'revenues',
            'bills' => 'bills',
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
     * Enable the specified resource.
     *
     * @param  Category  $category
     *
     * @return Response
     */
    public function enable(Category $category)
    {
        $category->enabled = 1;
        $category->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('general.categories', 1)]);

        flash($message)->success();

        return redirect()->route('categories.index');
    }

    /**
     * Disable the specified resource.
     *
     * @param  Category  $category
     *
     * @return Response
     */
    public function disable(Category $category)
    {
        $relationships = $this->countRelationships($category, [
            'items' => 'items',
            'invoices' => 'invoices',
            'revenues' => 'revenues',
            'bills' => 'bills',
            'payments' => 'payments',
        ]);

        if (empty($relationships)) {
            $category->enabled = 0;
            $category->save();

            $message = trans('messages.success.disabled', ['type' => trans_choice('general.categories', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.disabled', ['name' => $category->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect()->route('categories.index');
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
        // Can not delete the last category by type
        if (Category::where('type', $category->type)->count() == 1) {
            $message = trans('messages.error.last_category', ['type' => strtolower(trans_choice('general.' . $category->type . 's', 1))]);

            flash($message)->warning();

            return redirect('settings/categories');
        }

        $relationships = $this->countRelationships($category, [
            'items' => 'items',
            'invoices' => 'invoices',
            'revenues' => 'revenues',
            'bills' => 'bills',
            'payments' => 'payments',
        ]);

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

    public function category(Request $request)
    {
        $category = Category::create($request->all());

        return response()->json($category);
    }
}
