<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Setting\Category as Request;
use App\Models\Setting\Category;
use App\Transformers\Setting\Category as Transformer;
use Dingo\Api\Routing\Helpers;

class Categories extends ApiController
{
    use Helpers;

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
        return $this->response->item($category, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $category = Category::create($request->all());

        return $this->response->created(url('api/categories/'.$category->id));
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
        $category->update($request->all());

        return $this->response->item($category->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->response->noContent();
    }
}
