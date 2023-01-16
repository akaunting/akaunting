<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Widget as Request;
use App\Models\Common\Widget;
use App\Jobs\Common\CreateWidget;
use App\Jobs\Common\DeleteWidget;
use App\Jobs\Common\UpdateWidget;
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
        $request['settings'] = [
            'width' => $request->get('width'),
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

            $message = trans('messages.success.added', ['type' => $widget->name]);

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

            $message = trans('messages.success.updated', ['type' => $widget->name]);

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
        $response = $this->ajaxDispatch(new DeleteWidget($widget));

        $response['redirect'] = route('dashboard');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $widget->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
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
