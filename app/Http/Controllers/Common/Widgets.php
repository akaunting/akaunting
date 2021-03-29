<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Widget as Request;
use App\Models\Common\Widget;
use App\Utilities\Widgets as Utility;

class Widgets extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $widgets = Utility::getClasses('all');

        return response()->json($widgets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request['settings'] = [
            'width' => $request->get('width'),
        ];

        $widget = Widget::create($request->input());

        $settings = $widget->settings;

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => false,
            'message' => trans('messages.success.added', ['type' => $widget->name]),
            'data' => [
                'class'     => $widget->class,
                'name'      => $widget->name,
                'settings'  => $settings,
                'sort'      => $widget->sort,
            ],
            'redirect' => route('dashboard'),
        ]);
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
        $settings = $widget->settings;

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
        $request['settings'] = [
            'width' => $request->get('width'),
        ];

        $widget->update($request->input());

        $settings = $widget->settings;

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => false,
            'message' => trans('messages.success.added', ['type' => $widget->name]),
            'data' => [
                'class'     => $widget->class,
                'name'      => $widget->name,
                'settings'  => $settings,
                'sort'      => $widget->sort,
            ],
            'redirect' => route('dashboard'),
        ]);
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
        $message = trans('messages.success.deleted', ['type' => $widget->name]);

        $widget->delete();

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('dashboard'),
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
