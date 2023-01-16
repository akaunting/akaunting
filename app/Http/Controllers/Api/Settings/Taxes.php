<?php

namespace App\Http\Controllers\Api\Settings;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Tax as Request;
use App\Http\Resources\Setting\Tax as Resource;
use App\Jobs\Setting\CreateTax;
use App\Jobs\Setting\DeleteTax;
use App\Jobs\Setting\UpdateTax;
use App\Models\Setting\Tax;

class Taxes extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $taxes = Tax::collect();

        return Resource::collection($taxes);
    }

    /**
     * Display the specified resource.
     *
     * @param  Tax  $tax
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tax $tax)
    {
        return new Resource($tax);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $tax = $this->dispatch(new CreateTax($request));

        return $this->created(route('api.taxes.show', $tax->id), new Resource($tax));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $tax
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Tax $tax, Request $request)
    {
        try {
            $tax = $this->dispatch(new UpdateTax($tax, $request));

            return new Resource($tax->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Tax  $tax
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Tax $tax)
    {
        $tax = $this->dispatch(new UpdateTax($tax, request()->merge(['enabled' => 1])));

        return new Resource($tax->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Tax  $tax
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Tax $tax)
    {
        try {
            $tax = $this->dispatch(new UpdateTax($tax, request()->merge(['enabled' => 0])));

            return new Resource($tax->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tax $tax)
    {
        try {
            $this->dispatch(new DeleteTax($tax));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
