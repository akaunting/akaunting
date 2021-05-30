<?php

namespace App\Http\Controllers\Api\Settings;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Tax as Request;
use App\Jobs\Setting\CreateTax;
use App\Jobs\Setting\DeleteTax;
use App\Jobs\Setting\UpdateTax;
use App\Models\Setting\Tax;
use App\Transformers\Setting\Tax as Transformer;

class Taxes extends ApiController
{
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
        return $this->item($tax, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $tax = $this->dispatch(new CreateTax($request));

        return $this->response->created(route('api.taxes.show', $tax->id), $this->item($tax, new Transformer()));
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
        try {
            $tax = $this->dispatch(new UpdateTax($tax, $request));

            return $this->item($tax->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Tax  $tax
     * @return \Dingo\Api\Http\Response
     */
    public function enable(Tax $tax)
    {
        $tax = $this->dispatch(new UpdateTax($tax, request()->merge(['enabled' => 1])));

        return $this->item($tax->fresh(), new Transformer());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Tax  $tax
     * @return \Dingo\Api\Http\Response
     */
    public function disable(Tax $tax)
    {
        try {
            $tax = $this->dispatch(new UpdateTax($tax, request()->merge(['enabled' => 0])));

            return $this->item($tax->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tax  $tax
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Tax $tax)
    {
        try {
            $this->dispatch(new DeleteTax($tax));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
