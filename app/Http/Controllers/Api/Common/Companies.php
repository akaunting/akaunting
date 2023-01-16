<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Company as Request;
use App\Http\Resources\Common\Company as Resource;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\DeleteCompany;
use App\Jobs\Common\UpdateCompany;
use App\Models\Common\Company;
use App\Traits\Users;
use Illuminate\Http\Response;

class Companies extends ApiController
{
    use Users;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $companies = user()->companies()->collect();

        return Resource::collection($companies);
    }

    /**
     * Display the specified resource.
     *
     * @param  Company  $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Company $company)
    {
        try {
            // Check if user can access company
            $this->canAccess($company);

            return new Resource($company);
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $company = $this->dispatch(new CreateCompany($request));

        return $this->created(route('api.companies.show', $company->id), new Resource($company));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $company
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Company $company, Request $request)
    {
        try {
            $company = $this->dispatch(new UpdateCompany($company, $request));

            return new Resource($company->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Company  $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Company $company)
    {
        try {
            $company = $this->dispatch(new UpdateCompany($company, request()->merge(['enabled' => 1])));

            return new Resource($company->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Company  $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Company $company)
    {
        try {
            $company = $this->dispatch(new UpdateCompany($company, request()->merge(['enabled' => 0])));

            return new Resource($company->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        try {
            $this->dispatch(new DeleteCompany($company));

            return $this->noContent();
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Check user company assignment
     *
     * @param  Company  $company
     *
     * @return \Illuminate\Http\Response
     */
    public function canAccess(Company $company)
    {
        if (! empty($company) && $this->isUserCompany($company->id)) {
            return new Response('');
        }

        $message = trans('companies.error.not_user_company');

        $this->errorUnauthorized($message);
    }
}
