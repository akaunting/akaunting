<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Dashboard as Request;
use App\Http\Resources\Common\Dashboard as Resource;
use App\Jobs\Common\CreateDashboard;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\UpdateDashboard;
use App\Models\Common\Dashboard;
use App\Traits\Users;
use Illuminate\Http\Response;

class Dashboards extends ApiController
{
    use Users;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $dashboards = user()->dashboards()->with('widgets')->collect();

        return Resource::collection($dashboards);
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $dashboard = Dashboard::with('widgets')->find($id);

            if (! $dashboard instanceof Dashboard) {
                return $this->errorInternal('No query results for model [' . Dashboard::class . '] ' . $id);
            }

            // Check if user can access dashboard
            $this->canAccess($dashboard);

            return new Resource($dashboard);
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
        $dashboard = $this->dispatch(new CreateDashboard($request));

        return $this->created(route('api.dashboards.show', $dashboard->id), new Resource($dashboard));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $dashboard
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Dashboard $dashboard, Request $request)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, $request));

            return new Resource($dashboard->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Dashboard  $dashboard
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Dashboard $dashboard)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, request()->merge(['enabled' => 1])));

            return new Resource($dashboard->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Dashboard  $dashboard
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Dashboard $dashboard)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, request()->merge(['enabled' => 0])));

            return new Resource($dashboard->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        try {
            $this->dispatch(new DeleteDashboard($dashboard));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Check user dashboard assignment
     *
     * @param  Dashboard  $dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function canAccess($dashboard)
    {
        if (!empty($dashboard) && $this->isUserDashboard($dashboard->id)) {
            return new Response('');
        }

        $message = trans('dashboards.error.not_user_dashboard');

        $this->errorUnauthorized($message);
    }
}
