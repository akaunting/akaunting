<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\BulkAction as Request;

class

BulkActions extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @param  $group
     * @param  $type
     * @param  Request $request
     * @return Response
     */
    public function action($group, $type, Request $request)
    {
        if ($request->get('handle', '*') == '*') {
            return response()->json([
                'success' => false,
                'redirect' => true,
                'error' => true,
                'data' => [],
                'message' => ''
            ]);
        }

        // Check is module
        $module = module($group);

        if ($module instanceof \Akaunting\Module\Module) {
            $bulk_actions = app('Modules\\' . $module->getStudlyName() . '\BulkActions\\' . ucfirst($type));
        } else {
            $bulk_actions = app('App\BulkActions\\' .  ucfirst($group) . '\\' . ucfirst($type));
        }

        if (isset($bulk_actions->actions[$request->get('handle')]['permission']) && !user()->can($bulk_actions->actions[$request->get('handle')]['permission'])) {
            flash(trans('errors.message.403'))->error();

            return response()->json([
                'success' => false,
                'redirect' => true,
                'error' => true,
                'data' => [],
                'message' => trans('errors.message.403')
            ]);
        }

        $result = $bulk_actions->{$request->get('handle')}($request);

        if (!empty($result) && ($result instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse)) {
            return $result;
        } else {
            return response()->json([
                'success' => true,
                'redirect' => true,
                'error' => false,
                'data' => [],
                'message' => ''
            ]);
        }
    }
}
