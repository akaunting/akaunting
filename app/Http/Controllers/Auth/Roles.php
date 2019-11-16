<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Role as Request;
use App\Jobs\Auth\CreateRole;
use App\Jobs\Auth\DeleteRole;
use App\Jobs\Auth\UpdateRole;
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
        $names = $permissions = [];
        $allPermissions = Permission::all();

        foreach ($allPermissions as $permission) {
            // permission code explode - and get permission type
            $n = explode('-', $permission->name);

            if (!in_array($n[0], $names)) {
                $names[] = $n[0];
            }

            $permissions[$n[0]][] = $permission;
        }

        return view('auth.roles.create', compact('names', 'permissions'));
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
        $response = $this->ajaxDispatch(new CreateRole($request));

        if ($response['success']) {
            $response['redirect'] = route('roles.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.roles', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('roles.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
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
        $names = $permissions = [];
        $allPermissions = Permission::all();

        $rolePermissions = $role->permissions->pluck('id', 'id')->toArray();

        foreach ($allPermissions as $permission) {
            // permission code explode - and get permission type
            $n = explode('-', $permission->name);

            if (!in_array($n[0], $names)) {
                $names[] = $n[0];
            }

            $permissions[$n[0]][] = $permission;
        }

        return view('auth.roles.edit', compact('role', 'names', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Role $role
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Role $role, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateRole($role, $request));

        if ($response['success']) {
            $response['redirect'] = route('roles.index');

            $message = trans('messages.success.updated', ['type' => $role->display_name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('roles.edit', $role->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role $role
     *
     * @return Response
     */
    public function destroy(Role $role)
    {
        $response = $this->ajaxDispatch(new DeleteRole($role));

        $response['redirect'] = route('roles.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $role->display_name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }
}
