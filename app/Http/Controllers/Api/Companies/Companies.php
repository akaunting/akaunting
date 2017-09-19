<?php

namespace App\Http\Controllers\Api\Companies;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Company\Company as Request;
use App\Http\Transformers\Company\Company as Transformer;
use App\Models\Company\Company;
use Dingo\Api\Routing\Helpers;

class Companies extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $companies = app('Dingo\Api\Auth\Auth')->user()->companies()->get()->sortBy('name');

        foreach ($companies as $company) {
            $company->setSettings();
        }

        return $this->response->collection($companies, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Company  $company
     * @return \Dingo\Api\Http\Response
     */
    public function show(Company $company)
    {
        $company->setSettings();

        return $this->response->item($company, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $company = Company::create($request->all());

        return $this->response->created(url('api/companies/'.$company->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $company
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Company $company, Request $request)
    {
        // Check if user can access company
        $companies = app('Dingo\Api\Auth\Auth')->user()->companies()->pluck('id')->toArray();
        if (!in_array($company->id, $companies)) {
            return $this->response->noContent();
        }

        return $this->response->item($company->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Company  $company
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Company $company)
    {
        // Check if user can access company
        $companies = app('Dingo\Api\Auth\Auth')->user()->companies()->pluck('id')->toArray();

        if (in_array($company->id, $companies)) {
            $company->delete();
        }

        return $this->response->noContent();
    }
}
