<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Item as Request;
use App\Http\Resources\Common\Item as Resource;
use App\Jobs\Common\CreateItem;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\UpdateItem;
use App\Models\Common\Item;

class Items extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = Item::with('category', 'taxes')->collect();

        return Resource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $item = Item::with('category', 'taxes')->find($id);

        if (! $item instanceof Item) {
            return $this->errorInternal('No query results for model [' . Item::class . '] ' . $id);
        }

        return new Resource($item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $item = $this->dispatch(new CreateItem($request));

        return $this->created(route('api.items.show', $item->id), new Resource($item));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $item
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Item $item, Request $request)
    {
        $item = $this->dispatch(new UpdateItem($item, $request));

        return new Resource($item->fresh());
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Item $item)
    {
        $item = $this->dispatch(new UpdateItem($item, request()->merge(['enabled' => 1])));

        return new Resource($item->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Item $item)
    {
        $item = $this->dispatch(new UpdateItem($item, request()->merge(['enabled' => 0])));

        return new Resource($item->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        try {
            $this->dispatch(new DeleteItem($item));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
