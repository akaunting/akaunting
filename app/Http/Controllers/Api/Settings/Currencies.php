<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Setting\Currency as Request;
use App\Models\Setting\Currency;
use App\Transformers\Setting\Currency as Transformer;
use Dingo\Api\Routing\Helpers;

class Currencies extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $currencies = Currency::collect();

        return $this->response->paginator($currencies, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Currency  $currency
     * @return \Dingo\Api\Http\Response
     */
    public function show(Currency $currency)
    {
        return $this->response->item($currency, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $currency = Currency::create($request->all());

        return $this->response->created(url('api/currencies/'.$currency->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $currency
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Currency $currency, Request $request)
    {
        $currency->update($request->all());

        return $this->response->item($currency->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Currency  $currency
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();

        return $this->response->noContent();
    }
}
