<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Company as Request;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\DeleteCompany;
use App\Jobs\Common\UpdateCompany;
use App\Models\Common\Company;
use App\Transformers\Common\Company as Transformer;
use App\Traits\Users;
use Dingo\Api\Http\Response;

class Companies extends ApiController
{
    use Users;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $companies = user()->companies()->collect();

        return $this->response->paginator($companies, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Company  $company
     * @return \Dingo\Api\Http\Response
     */
    public function show(Company $company)
    {
        try {
            // Check if user can access company
            $this->canAccess($company);

            return $this->item($company, new Transformer());
        } catch (\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $company = $this->dispatch(new CreateCompany($request));

        return $this->response->created(route('api.companies.show', $company->id), $this->item($company, new Transformer()));
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
        try {
            $company = $this->dispatch(new UpdateCompany($company, $request));

            return $this->item($company->fresh(), new Transformer());
        } catch (\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Company  $company
     * @return \Dingo\Api\Http\Response
     */
    public function enable(Company $company)
    {
        try {
            $company = $this->dispatch(new UpdateCompany($company, request()->merge(['enabled' => 1])));

            return $this->item($company->fresh(), new Transformer());
        } catch (\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Company  $company
     * @return \Dingo\Api\Http\Response
     */
    public function disable(Company $company)
    {
        try {
            $company = $this->dispatch(new UpdateCompany($company, request()->merge(['enabled' => 0])));

            return $this->item($company->fresh(), new Transformer());
        } catch (\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Company  $company
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Company $company)
    {
        try {
            $this->dispatch(new DeleteCompany($company));

            return $this->response->noContent();
        } catch (\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Check user company assignment
     *
     * @param  Company  $company
     *
     * @return \Dingo\Api\Http\Response
     */
    public function canAccess(Company $company)
    {
        if (!empty($company) && $this->isUserCompany($company->id)) {
            return new Response('');
        }

        $message = trans('companies.error.not_user_company');

        $this->response->errorUnauthorized($message);
    }
}
