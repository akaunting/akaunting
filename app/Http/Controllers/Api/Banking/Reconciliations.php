<?php

namespace App\Http\Controllers\Api\Banking;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Reconciliation as Request;
use App\Http\Resources\Banking\Reconciliation as Resource;
use App\Jobs\Banking\CreateReconciliation;
use App\Jobs\Banking\DeleteReconciliation;
use App\Jobs\Banking\UpdateReconciliation;
use App\Models\Banking\Reconciliation;

class Reconciliations extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $reconciliations = Reconciliation::with('account')->collect();

        return Resource::collection($reconciliations);
    }

    /**
     * Display the specified resource.
     *
     * @param  $reconciliation
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Reconciliation $reconciliation)
    {
        return new Resource($reconciliation);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $reconciliation = $this->dispatch(new CreateReconciliation($request));

        return $this->created(route('api.reconciliations.show', $reconciliation->id), new Resource($reconciliation));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $reconciliation
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Reconciliation $reconciliation, Request $request)
    {
        $reconciliation = $this->dispatch(new UpdateReconciliation($reconciliation, $request));

        return new Resource($reconciliation->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reconciliation $reconciliation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reconciliation $reconciliation)
    {
        try {
            $this->dispatch(new DeleteReconciliation($reconciliation));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
