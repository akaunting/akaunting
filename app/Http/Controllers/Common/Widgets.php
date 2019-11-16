<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;

use App\Models\Common\DashboardWidget as Model;
use App\Models\Common\Widget;
use App\Http\Requests\Common\Widget as Request;

class Widgets extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dashboard_widgets = Widget::enabled()->get();

        return response()->json($dashboard_widgets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request['user_id'] = user()->id;

        $request['settings'] = [
            'width' => $request->get('width')
        ];

        $widget = Model::create($request->input());

        $settings = $widget->settings;
        unset($settings['widget']);

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => false,
            'message' => trans('messages.success.added', ['type' => $widget->name]),
            'data' => [
                'widget_id' => $widget->widget_id,
                'name' => $widget->name,
                'settings' => $settings,
                'sort' => $widget->sort,
            ],
            'redirect' => route('dashboard')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Model  $dashboard
     *
     * @return Response
     */
    public function edit(Model $widget)
    {
        $settings = $widget->settings;
        unset($settings['widget']);

        return response()->json([
            'widget_id' => $widget->widget_id,
            'name' => $widget->name,
            'settings' => $settings,
            'sort' => $widget->sort,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Model  $dashboard
     * @param  $request
     * @return Response
     */
    public function update(Model $widget, Request $request)
    {
        $request['user_id'] = user()->id;

        $request['settings'] = [
            'width' => $request->get('width')
        ];

        $widget->update($request->input());

        $settings = $widget->settings;
        unset($settings['widget']);

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => false,
            'message' => trans('messages.success.added', ['type' => $widget->name]),
            'data' => [
                'widget_id' => $widget->widget_id,
                'name' => $widget->name,
                'settings' => $settings,
                'sort' => $widget->sort,
            ],
            'redirect' => route('dashboard')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Model $dashboard
     *
     * @return Response
     */
    public function destroy(Model $widget)
    {
        $message = trans('messages.success.deleted', ['type' => $widget->name]);

        $widget->delete();

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('dashboard')
        ]);
    }

    public function getData(Request $request)
    {
        // Check is module
        $module = module($request->get('widget'));

        if ($module instanceof \Akaunting\Module\Module) {
            $widget = app('Modules\\' . $module->getStudlyName() . '\Widgets\\' . ucfirst($request->get('widget')));
        } else {
            $widget = app('App\Widgets\\' .  ucfirst($request->get('widget')));
        }

        $response = $widget->{$request->get('method')}($request);

        return response()->json($response);
    }
}
