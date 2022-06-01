<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\BulkAction as Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class BulkActions extends Controller
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
        $handle = $request->get('handle', '*');

        if ($handle == '*') {
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
        $page = ucfirst($type);

        if ($module instanceof \Akaunting\Module\Module) {
            $tmp = explode('.', $type);
            $file_name = !empty($tmp[1]) ? Str::studly($tmp[0]) . '\\' . Str::studly($tmp[1]) : Str::studly($tmp[0]);

            $bulk_actions = app('Modules\\' . $module->getStudlyName() . '\BulkActions\\' . $file_name);

            $page = ucfirst($file_name);
        } else {
            $bulk_actions = app('App\BulkActions\\' .  ucfirst($group) . '\\' . ucfirst($type));
        }

        if (isset($bulk_actions->actions[$handle]['permission']) && !user()->can($bulk_actions->actions[$handle]['permission'])) {
            flash(trans('errors.message.403'))->error()->important();

            return response()->json([
                'success' => false,
                'redirect' => true,
                'error' => true,
                'data' => [],
                'message' => trans('errors.message.403')
            ]);
        }

        $result = $bulk_actions->{$handle}($request);

        $message = trans($bulk_actions->messages['general'], ['type' => $handle, 'count' => count($request->get('selected'))]);

        if (array_key_exists($handle, $bulk_actions->messages)) {
            $message = trans($bulk_actions->messages[$handle], ['type' => $page]);
        }

        if (! empty($result) && ($result instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse)) {
            flash($message)->success();

            return $result;
        } elseif (! empty($result) && ($result instanceof RedirectResponse)) {
            flash($message)->success();

            return response()->json([
                'success' => true,
                'redirect' => $result->getTargetUrl(),
                'error' => false,
                'data' => [],
                'message' => ''
            ]);
        } else {
            flash($message)->success();

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
