<?php

namespace App\Http\Controllers\Api\Banking;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Reconciliation as Request;
use App\Jobs\Banking\CreateReconciliation;
use App\Jobs\Banking\DeleteReconciliation;
use App\Jobs\Banking\UpdateReconciliation;
use App\Models\Banking\Reconciliation;
use App\Transformers\Banking\Reconciliation as Transformer;

class Reconciliations extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $items = Reconciliation::with('account')->collect();

        return $this->response->paginator($items, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  $reconciliation
     * @return \Dingo\Api\Http\Response
     */
    public function show(Reconciliation $reconciliation)
    {
        return $this->item($reconciliation, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $reconciliation = $this->dispatch(new CreateReconciliation($request));

        return $this->response->created(route('api.reconciliations.show', $reconciliation->id), $this->item($reconciliation, new Transformer()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $reconciliation
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Reconciliation $reconciliation, Request $request)
    {
        $reconciliation = $this->dispatch(new UpdateReconciliation($reconciliation, $request));

        return $this->item($reconciliation->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reconciliation $reconciliation
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Reconciliation $reconciliation)
    {
        try {
            $this->dispatch(new DeleteReconciliation($reconciliation));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
