<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Item as Request;
use App\Jobs\Common\CreateItem;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\UpdateItem;
use App\Models\Common\Item;
use App\Transformers\Common\Item as Transformer;

class Items extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $items = Item::with('category', 'taxes')->collect();

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
        $item = Item::with('category', 'taxes')->find($id);

        return $this->item($item, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $item = $this->dispatch(new CreateItem($request));

        return $this->response->created(route('api.items.show', $item->id), $this->item($item, new Transformer()));
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
        $item = $this->dispatch(new UpdateItem($item, $request));

        return $this->item($item->fresh(), new Transformer());
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Item  $item
     * @return \Dingo\Api\Http\Response
     */
    public function enable(Item $item)
    {
        $item = $this->dispatch(new UpdateItem($item, request()->merge(['enabled' => 1])));

        return $this->item($item->fresh(), new Transformer());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Item  $item
     * @return \Dingo\Api\Http\Response
     */
    public function disable(Item $item)
    {
        $item = $this->dispatch(new UpdateItem($item, request()->merge(['enabled' => 0])));

        return $this->item($item->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item  $item
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Item $item)
    {
        try {
            $this->dispatch(new DeleteItem($item));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
