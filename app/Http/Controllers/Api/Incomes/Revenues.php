<?php

namespace App\Http\Controllers\Api\Incomes;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Income\Revenue as Request;
use App\Models\Income\Revenue;
use App\Transformers\Income\Revenue as Transformer;
use Dingo\Api\Routing\Helpers;

class Revenues extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $revenues = Revenue::with(['account', 'customer', 'category'])->collect();

        return $this->response->paginator($revenues, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Revenue  $revenue
     * @return \Dingo\Api\Http\Response
     */
    public function show(Revenue $revenue)
    {
        return $this->response->item($revenue, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $revenue = Revenue::create($request->all());

        return $this->response->created(url('api/revenues/'.$revenue->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $revenue
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Revenue $revenue, Request $request)
    {
        $revenue->update($request->all());

        return $this->response->item($revenue->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Revenue  $revenue
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Revenue $revenue)
    {
        $revenue->delete();

        return $this->response->noContent();
    }
}
