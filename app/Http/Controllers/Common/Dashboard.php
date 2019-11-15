<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;

use App\Models\Common\Dashboard as Model;
use App\Models\Common\DashboardWidget;
use App\Http\Requests\Common\Dashboard as Request;

class Dashboard extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dashboard_id = session('dashboard_id', 0);

        // Change Dashboard
        if (request()->get('dashboard_id', 0)) {
            $dashboard_id = request()->get('dashboard_id');

            session(['dashboard_id' => $dashboard_id]);
        }

        $user_id = user()->id;

        $dashboards = Model::where('user_id', $user_id)->enabled()->get();

        if (!$dashboard_id) {
            $dashboard_id = $dashboards->first()->id;
        }

        // Dashboard
        $dashboard = Model::find($dashboard_id);

        // Dashboard Widgets
        $widgets = DashboardWidget::where('dashboard_id', $dashboard->id)
            ->where('user_id', $user_id)
            ->orderBy('sort', 'asc')->get();

        return view('common.dashboard.index', compact('dashboards','dashboard', 'widgets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request['enabled'] = 1;
        $request['user_id'] = user()->id;

        $dashboard = Model::create($request->input());

        $response['data'] = $dashboard;
        $response['redirect'] = route('dashboard');

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Model  $dashboard
     *
     * @return Response
     */
    public function edit(Model $dashboard)
    {
        return response()->json($dashboard);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Model  $dashboard
     * @param  $request
     * @return Response
     */
    public function update(Model $dashboard, Request $request)
    {
        $request['enabled'] = 1;
        $dashboard->update($request->input());

        $response['data'] = $dashboard;
        $response['redirect'] = route('dashboard');

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Model $dashboard
     *
     * @return Response
     */
    public function destroy(Model $dashboard)
    {
        $dashboard->delete();

        session(['dashboard_id' => user()->dashboards()->pluck('id')->first()]);

        $response['redirect'] = route('dashboard');

        return response()->json($response);
    }
}
