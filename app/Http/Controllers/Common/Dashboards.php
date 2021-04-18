<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Dashboard as Request;
use App\Jobs\Common\CreateDashboard;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\UpdateDashboard;
use App\Models\Common\Dashboard;
use App\Models\Common\Widget;
use App\Traits\DateTime;
use App\Traits\Users;
use App\Utilities\Widgets;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Dashboards extends Controller
{
    use DateTime, Users;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-common-dashboards')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-common-dashboards')->only('show');
        $this->middleware('permission:update-common-dashboards')->only('index', 'edit', 'export', 'update', 'enable', 'disable', 'share');
        $this->middleware('permission:delete-common-dashboards')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dashboards = user()->dashboards()->collect();

        return $this->response('common.dashboards.index', compact('dashboards'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show($dashboard_id = null)
    {
        $dashboard_id = $dashboard_id ?? session('dashboard_id');

        try {
            $dashboard = Dashboard::findOrFail($dashboard_id);
        } catch (ModelNotFoundException $e) {
            $dashboard = user()->dashboards()->enabled()->first();
        }

        if (empty($dashboard)) {
            $dashboard = $this->dispatch(new CreateDashboard([
                'company_id' => company_id(),
                'name' => trans_choice('general.dashboards', 1),
                'default_widgets' => 'core',
            ]));
        }

        session(['dashboard_id' => $dashboard->id]);

        $widgets = Widget::where('dashboard_id', $dashboard->id)->orderBy('sort', 'asc')->get()->filter(function ($widget) {
            return Widgets::canShow($widget->class);
        });

        $date_picker_shortcuts = $this->getDatePickerShortcuts();

        if (!request()->has('start_date')) {
            request()->merge(['start_date' => $date_picker_shortcuts[trans('reports.this_year')]['start']]);
            request()->merge(['end_date' => $date_picker_shortcuts[trans('reports.this_year')]['end']]);
        }

        return view('common.dashboards.show', compact('dashboard', 'widgets', 'date_picker_shortcuts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $users = company()->users()->get()->sortBy('name');

        return view('common.dashboards.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateDashboard($request));

        if ($response['success']) {
            $response['redirect'] = route('dashboards.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.dashboards', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('dashboards.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Dashboard  $dashboard
     *
     * @return Response
     */
    public function edit(Dashboard $dashboard)
    {
        if ($this->isNotUserDashboard($dashboard->id)) {
            return redirect()->route('dashboards.index');
        }

        $users = company()->users()->get()->sortBy('name');

        return view('common.dashboards.edit', compact('dashboard', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Dashboard  $dashboard
     * @param  $request
     * @return Response
     */
    public function update(Dashboard $dashboard, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDashboard($dashboard, $request));

        if ($response['success']) {
            $response['redirect'] = route('dashboards.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('general.dashboards', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('dashboards.edit', $dashboard->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Dashboard $dashboard
     *
     * @return Response
     */
    public function enable(Dashboard $dashboard)
    {
        $response = $this->ajaxDispatch(new UpdateDashboard($dashboard, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans_choice('general.dashboards', 1)]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Dashboard $dashboard
     *
     * @return Response
     */
    public function disable(Dashboard $dashboard)
    {
        $response = $this->ajaxDispatch(new UpdateDashboard($dashboard, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => trans_choice('general.dashboards', 1)]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Dashboard $dashboard
     *
     * @return Response
     */
    public function destroy(Dashboard $dashboard)
    {
        $response = $this->ajaxDispatch(new DeleteDashboard($dashboard));

        $response['redirect'] = route('dashboards.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $dashboard->name]);

            flash($message)->success();

            session(['dashboard_id' => user()->dashboards()->enabled()->pluck('id')->first()]);
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Change the active dashboard.
     *
     * @param  Dashboard  $dashboard
     *
     * @return Response
     */
    public function switch(Dashboard $dashboard)
    {
        if ($this->isUserDashboard($dashboard->id)) {
            session(['dashboard_id' => $dashboard->id]);
        }

        return redirect()->route('dashboard');
    }
}
