<?php

namespace App\Http\Controllers\Api\Settings;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Currency as Request;
use App\Jobs\Setting\CreateCurrency;
use App\Jobs\Setting\DeleteCurrency;
use App\Jobs\Setting\UpdateCurrency;
use App\Models\Setting\Currency;
use App\Transformers\Setting\Currency as Transformer;

class Currencies extends ApiController
{
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
     * @param  int|string  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // Check if we're querying by id or code
        if (is_numeric($id)) {
            $currency = Currency::find($id);
        } else {
            $currency = Currency::where('code', $id)->first();
        }

        return $this->item($currency, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $currency = $this->dispatch(new CreateCurrency($request));

        return $this->response->created(route('api.currencies.show', $currency->id), $this->item($currency, new Transformer()));
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
        try {
            $currency = $this->dispatch(new UpdateCurrency($currency, $request));

            return $this->item($currency->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Currency  $currency
     * @return \Dingo\Api\Http\Response
     */
    public function enable(Currency $currency)
    {
        $currency = $this->dispatch(new UpdateCurrency($currency, request()->merge(['enabled' => 1])));

        return $this->item($currency->fresh(), new Transformer());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Currency  $currency
     * @return \Dingo\Api\Http\Response
     */
    public function disable(Currency $currency)
    {
        try {
            $currency = $this->dispatch(new UpdateCurrency($currency, request()->merge(['enabled' => 0])));

            return $this->item($currency->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Currency  $currency
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Currency $currency)
    {
        try {
            $this->dispatch(new DeleteCurrency($currency));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
