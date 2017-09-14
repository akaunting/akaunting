<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Role as Request;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;

class Roles extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::collect();

        return view('auth.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('auth.roles.create', compact('permissions'));
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
        // Create role
        $role = Role::create($request->all());

        // Attach permissions
        $role->permissions()->attach($request['permissions']);

        $message = trans('messages.success.added', ['type' => trans_choice('general.roles', 1)]);

        flash($message)->success();

        return redirect('auth/roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role  $role
     *
     * @return Response
     */
    public function edit(Role $role)
    {
        //$permissions = Permission::all()->sortBy('display_name');
        $permissions = Permission::all();

        $rolePermissions = $role->permissions->pluck('id', 'id')->toArray();

        return view('auth.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Role  $role
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Role $role, Request $request)
    {
        // Update role
        $role->update($request->all());

        // Sync permissions
        $role->permissions()->sync($request['permissions']);

        $message = trans('messages.success.updated', ['type' => trans_choice('general.roles', 1)]);

        flash($message)->success();

        return redirect('auth/roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     *
     * @return Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.roles', 1)]);

        flash($message)->success();

        return redirect('auth/roles');
    }
}
