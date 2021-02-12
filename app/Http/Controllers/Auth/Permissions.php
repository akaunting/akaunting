<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Permission as Request;
use App\Jobs\Auth\CreatePermission;
use App\Jobs\Auth\DeletePermission;
use App\Jobs\Auth\UpdatePermission;
use App\Models\Auth\Permission;

class Permissions extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $permissions = Permission::collect();

        return $this->response('auth.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('auth.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreatePermission($request));

        if ($response['success']) {
            $response['redirect'] = route('permissions.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.permissions', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('permissions.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Permission  $permission
     *
     * @return Response
     */
    public function edit(Permission $permission)
    {
        return view('auth.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Permission $permission
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Permission $permission, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdatePermission($permission, $request));

        if ($response['success']) {
            $response['redirect'] = route('permissions.index');

            $message = trans('messages.success.updated', ['type' => $permission->display_name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('permissions.edit', $permission->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Permission $permission
     *
     * @return Response
     */
    public function destroy(Permission $permission)
    {
        $response = $this->ajaxDispatch(new DeletePermission($permission));

        $response['redirect'] = route('permissions.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $permission->display_name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
}
