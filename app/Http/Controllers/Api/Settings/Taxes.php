<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Setting\Tax as Request;
use App\Models\Setting\Tax;
use App\Transformers\Setting\Tax as Transformer;
use Dingo\Api\Routing\Helpers;

class Taxes extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $taxes = Tax::collect();

        return $this->response->paginator($taxes, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Tax  $tax
     * @return \Dingo\Api\Http\Response
     */
    public function show(Tax $tax)
    {
        return $this->response->item($tax, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $tax = Tax::create($request->all());

        return $this->response->created(url('api/taxes/'.$tax->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $tax
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Tax $tax, Request $request)
    {
        $tax->update($request->all());

        return $this->response->item($tax->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tax  $tax
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Tax $tax)
    {
        $tax->delete();

        return $this->response->noContent();
    }
}
