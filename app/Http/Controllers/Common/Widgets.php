<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Widget as Request;
use App\Models\Common\Widget;
use App\Jobs\Common\CreateWidget;
use App\Jobs\Common\DeleteWidget;
use App\Jobs\Common\UpdateWidget;
use App\Traits\Users;
use App\Utilities\Widgets as Utility;

class Widgets extends Controller
{
    use Users;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:read-common-widgets')->only('getData');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $widgets = Utility::getClasses('all');

        $settings = [];

        foreach ($widgets as $class => $name) {
            $settings[$class] = (new $class())->getDefaultSettings();
        }

        return response()->json([
            'types' => $widgets,
            'settings' => $settings,
        ]);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if ($this->isNotUserDashboard($request->get('dashboard_id'))) {
            return $this->notUserDashboardResponse();
        }

        $request['settings'] = [
            'width' => $request->get('width'),
            'limit' => $request->get('limit'),
        ];

        $response = $this->ajaxDispatch(new CreateWidget($request));

        if ($response['success']) {
            $response['redirect'] = route('dashboard');

            $widget = $response['data'];

            $settings = $widget->settings;

            $response['data'] = [
                'class'     => $widget->class,
                'name'      => $widget->name,
                'settings'  => $settings,
                'sort'      => $widget->sort,
            ];

            $message = trans('messages.success.added', ['type' => escape_html($widget->name)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('dashboard');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Widget  $widget
     *
     * @return Response
     */
    public function edit(Widget $widget)
    {
        if ($this->isNotUserDashboard($widget->dashboard_id)) {
            abort(403);
        }

        $settings = $widget->settings;

        $class_name = $widget->class;

        // Backfill any setting the widget declares but that isn't stored yet
        // (older widgets, or a value never explicitly set) with the widget's
        // own default, so the edit form shows the value actually in effect
        // instead of a blank/null field.
        foreach ((new $class_name())->getDefaultSettings() as $key => $default_value) {
            if (! isset($settings->{$key})) {
                $settings->{$key} = $default_value;
            }
        }

        return response()->json([
            'class' => $widget->class,
            'name' => $widget->name,
            'settings' => $settings,
            'sort' => $widget->sort,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Widget  $widget
     * @param  $request
     * @return Response
     */
    public function update(Widget $widget, Request $request)
    {
        // Both the dashboard the widget is on and the one it may move to
        if ($this->isNotUserDashboard($widget->dashboard_id) || $this->isNotUserDashboard($request->get('dashboard_id'))) {
            return $this->notUserDashboardResponse();
        }

        $request['settings'] = [
            'width' => $request->get('width'),
            'limit' => $request->get('limit'),
        ];

        $response = $this->ajaxDispatch(new UpdateWidget($widget, $request));

        $response['redirect'] = route('dashboard');

        if ($response['success']) {
            $settings = $response['data']->settings;

            $response['data'] = [
                'class'     => $widget->class,
                'name'      => $widget->name,
                'settings'  => $settings,
                'sort'      => $widget->sort,
            ];

            $message = trans('messages.success.updated', ['type' => escape_html($widget->name)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Widget $widget
     *
     * @return Response
     */
    public function destroy(Widget $widget)
    {
        if ($this->isNotUserDashboard($widget->dashboard_id)) {
            return $this->notUserDashboardResponse();
        }

        $response = $this->ajaxDispatch(new DeleteWidget($widget));

        $response['redirect'] = route('dashboard');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => escape_html($widget->name)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Widgets can only be changed on the dashboards of the logged in user.
     */
    protected function notUserDashboardResponse()
    {
        $message = trans('dashboards.error.not_user_dashboard');

        flash($message)->error()->important();

        return response()->json([
            'success' => false,
            'error' => true,
            'data' => null,
            'message' => $message,
            'redirect' => route('dashboard'),
        ], 403);
    }

    public function getData(Request $request)
    {
        $widget_name = $request->get('widget');
        $method = $request->get('method', 'show');

        // Security: validate the widget name contains only alphanumeric
        // characters to prevent namespace traversal.
        if (empty($widget_name) || ! preg_match('/^[a-zA-Z0-9]+$/', $widget_name)) {
            abort(404);
        }

        // Check is module
        $module = module($widget_name);

        if ($module instanceof \Akaunting\Module\Module) {
            $widget = app('Modules\\' . $module->getStudlyName() . '\\Widgets\\' . ucfirst($widget_name));
        } else {
            $widget = app('App\Widgets\\' .  ucfirst($widget_name));
        }

        // Security: ensure the resolved instance is a Widget subclass.
        if (! ($widget instanceof \App\Abstracts\Widget)) {
            abort(404);
        }

        // Security: only allow methods declared in the widget's $allowed_methods
        // array. This prevents calling arbitrary internal methods (applyFilters,
        // calculateDocumentTotals, setData, etc.) that could be abused.
        // Subclasses can extend $allowed_methods for their own safe, read-only methods.
        if (! in_array($method, $widget->allowed_methods)) {
            abort(403);
        }

        $response = $widget->{$method}($request);

        return response()->json($response);
    }
}
