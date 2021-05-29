<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Dashboard as Request;
use App\Jobs\Common\CreateDashboard;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\UpdateDashboard;
use App\Models\Common\Dashboard;
use App\Transformers\Common\Dashboard as Transformer;
use App\Traits\Users;
use Dingo\Api\Http\Response;

class Dashboards extends ApiController
{
    use Users;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $dashboards = user()->dashboards()->with('widgets')->collect();

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
        try {
            $dashboard = Dashboard::with('widgets')->find($id);

            // Check if user can access dashboard
            $this->canAccess($dashboard);

            return $this->item($dashboard, new Transformer());
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
        $dashboard = $this->dispatch(new CreateDashboard($request));

        return $this->response->created(route('api.dashboards.show', $dashboard->id), $this->item($dashboard, new Transformer()));
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

    /**
     * Check user dashboard assignment
     *
     * @param  Dashboard  $dashboard
     *
     * @return \Dingo\Api\Http\Response
     */
    public function canAccess($dashboard)
    {
        if (!empty($dashboard) && $this->isUserDashboard($dashboard->id)) {
            return new Response('');
        }

        $message = trans('dashboards.error.not_user_dashboard');

        $this->response->errorUnauthorized($message);
    }
}
