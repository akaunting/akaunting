<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Dashboard as Request;
use App\Jobs\Common\CreateDashboard;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\UpdateDashboard;
use App\Models\Common\Dashboard;
use App\Transformers\Common\Dashboard as Transformer;

class Dashboards extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $dashboards = Dashboard::with('widgets')->collect();

        return $this->response->paginator($dashboards, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $dashboard = Dashboard::with('widgets')->find($id);

        return $this->response->item($dashboard, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $dashboard = $this->dispatch(new CreateDashboard($request));

        return $this->response->created(route('api.dashboards.show', $dashboard->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $dashboard
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Dashboard $dashboard, Request $request)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, $request));

            return $this->item($dashboard->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Dashboard  $dashboard
     * @return \Dingo\Api\Http\Response
     */
    public function enable(Dashboard $dashboard)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, request()->merge(['enabled' => 1])));

            return $this->item($dashboard->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Dashboard  $dashboard
     * @return \Dingo\Api\Http\Response
     */
    public function disable(Dashboard $dashboard)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, request()->merge(['enabled' => 0])));

            return $this->item($dashboard->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Dashboard  $dashboard
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        try {
            $this->dispatch(new DeleteDashboard($dashboard));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
