<?php

namespace App\Http\Controllers\Api\Settings;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Category as Request;
use App\Jobs\Setting\CreateCategory;
use App\Jobs\Setting\DeleteCategory;
use App\Jobs\Setting\UpdateCategory;
use App\Models\Setting\Category;
use App\Transformers\Setting\Category as Transformer;

class Categories extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $categories = Category::collect();

        return $this->response->paginator($categories, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     * @return \Dingo\Api\Http\Response
     */
    public function show(Category $category)
    {
        return $this->item($category, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $category = $this->dispatch(new CreateCategory($request));

        return $this->response->created(route('api.categories.show', $category->id), $this->item($category, new Transformer()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $category
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Category $category, Request $request)
    {
        try {
            $category = $this->dispatch(new UpdateCategory($category, $request));

            return $this->item($category->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Category  $category
     * @return \Dingo\Api\Http\Response
     */
    public function enable(Category $category)
    {
        $category = $this->dispatch(new UpdateCategory($category, request()->merge(['enabled' => 1])));

        return $this->item($category->fresh(), new Transformer());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Category  $category
     * @return \Dingo\Api\Http\Response
     */
    public function disable(Category $category)
    {
        try {
            $category = $this->dispatch(new UpdateCategory($category, request()->merge(['enabled' => 0])));

            return $this->item($category->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $this->dispatch(new DeleteCategory($category));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
