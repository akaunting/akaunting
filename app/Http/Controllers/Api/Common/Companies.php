<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Common\Company as Request;
use App\Models\Common\Company;
use App\Transformers\Common\Company as Transformer;
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
        // Check if user can access company
        $companies = app('Dingo\Api\Auth\Auth')->user()->companies()->pluck('id')->toArray();
        if (!in_array($company->id, $companies)) {
            $this->response->errorUnauthorized();
        }

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

        // Clear settings
        setting()->forgetAll();
        setting()->setExtraColumns(['company_id' => $company->id]);

        // Create settings
        setting()->set([
            'general.company_name' => $request->get('company_name'),
            'general.company_email' => $request->get('company_email'),
            'general.company_address' => $request->get('company_address'),
            'general.default_currency' => $request->get('default_currency'),
            'general.default_locale' => $request->get('default_locale', 'en-GB'),
        ]);

        setting()->save();

        return $this->response->created(url('api/companies/' . $company->id));
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
            $this->response->errorUnauthorized();
        }

        // Update company
        $company->update(['domain' => $request->get('domain')]);

        // Update settings
        setting()->forgetAll();
        setting()->setExtraColumns(['company_id' => $company->id]);
        setting()->load(true);

        setting()->set([
            'general.company_name' => $request->get('company_name'),
            'general.company_email' => $request->get('company_email'),
            'general.company_address' => $request->get('company_address'),
            'general.default_currency' => $request->get('default_currency'),
            'general.default_locale' => $request->get('default_locale', 'en-GB'),
        ]);

        setting()->save();

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
        if (!in_array($company->id, $companies)) {
            $this->response->errorUnauthorized();
        }

        $company->delete();

        return $this->response->noContent();
    }
}
