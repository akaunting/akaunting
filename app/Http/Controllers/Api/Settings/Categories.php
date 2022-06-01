<?php

namespace App\Http\Controllers\Api\Settings;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Category as Request;
use App\Http\Resources\Setting\Category as Resource;
use App\Jobs\Setting\CreateCategory;
use App\Jobs\Setting\DeleteCategory;
use App\Jobs\Setting\UpdateCategory;
use App\Models\Setting\Category;

class Categories extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::withSubCategory()->collect();

        return Resource::collection($categories);
    }

    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return new Resource($category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $category = $this->dispatch(new CreateCategory($request));

        return $this->created(route('api.categories.show', $category->id), new Resource($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $category
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Category $category, Request $request)
    {
        try {
            $category = $this->dispatch(new UpdateCategory($category, $request));

            return new Resource($category->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Category $category)
    {
        $category = $this->dispatch(new UpdateCategory($category, request()->merge(['enabled' => 1])));

        return new Resource($category->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Category $category)
    {
        try {
            $category = $this->dispatch(new UpdateCategory($category, request()->merge(['enabled' => 0])));

            return new Resource($category->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $this->dispatch(new DeleteCategory($category));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
