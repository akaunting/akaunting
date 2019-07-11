<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Common\Item as Request;
use App\Models\Common\Item;
use App\Transformers\Common\Item as Transformer;
use Dingo\Api\Routing\Helpers;

class Items extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $items = Item::with(['category', 'tax'])->collect();

        return $this->response->paginator($items, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // Check if we're querying by id or sku
        if (is_numeric($id)) {
            $item = Item::with(['category', 'tax'])->find($id);
        } else {
            $item = Item::with(['category', 'tax'])->where('sku', $id)->first();
        }

        return $this->response->item($item, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $item = Item::create($request->all());

        return $this->response->created(url('api/items/'.$item->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $item
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Item $item, Request $request)
    {
        $item->update($request->all());

        return $this->response->item($item->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item  $item
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return $this->response->noContent();
    }
}
