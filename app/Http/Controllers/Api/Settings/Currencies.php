<?php

namespace App\Http\Controllers\Api\Settings;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Currency as Request;
use App\Http\Resources\Setting\Currency as Resource;
use App\Jobs\Setting\CreateCurrency;
use App\Jobs\Setting\DeleteCurrency;
use App\Jobs\Setting\UpdateCurrency;
use App\Models\Setting\Currency;

class Currencies extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $currencies = Currency::collect();

        return Resource::collection($currencies);
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Check if we're querying by id or code
        if (is_numeric($id)) {
            $currency = Currency::find($id);
        } else {
            $currency = Currency::where('code', $id)->first();
        }

        if (! $currency instanceof Currency) {
            return $this->errorInternal('No query results for model [' . Currency::class . '] ' . $id);
        }

        return new Resource($currency);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $currency = $this->dispatch(new CreateCurrency($request));

        return $this->created(route('api.currencies.show', $currency->id), new Resource($currency));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $currency
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Currency $currency, Request $request)
    {
        try {
            $currency = $this->dispatch(new UpdateCurrency($currency, $request));

            return new Resource($currency->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Currency  $currency
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Currency $currency)
    {
        $currency = $this->dispatch(new UpdateCurrency($currency, request()->merge(['enabled' => 1])));

        return new Resource($currency->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Currency  $currency
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Currency $currency)
    {
        try {
            $currency = $this->dispatch(new UpdateCurrency($currency, request()->merge(['enabled' => 0])));

            return new Resource($currency->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        try {
            $this->dispatch(new DeleteCurrency($currency));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
