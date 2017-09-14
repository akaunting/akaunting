<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Permission as Request;
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

        return view('auth.permissions.index', compact('permissions'));
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
        // Create permission
        $permission = Permission::create($request->all());

        $message = trans('messages.success.added', ['type' => trans_choice('general.permissions', 1)]);

        flash($message)->success();

        return redirect('auth/permissions');
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
     * @param  Permission  $permission
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Permission $permission, Request $request)
    {
        // Update permission
        $permission->update($request->all());

        $message = trans('messages.success.updated', ['type' => trans_choice('general.permissions', 1)]);

        flash($message)->success();

        return redirect('auth/permissions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Permission  $permission
     *
     * @return Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.permissions', 1)]);

        flash($message)->success();

        return redirect('auth/permissions');
    }
}
