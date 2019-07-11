<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\Role as Request;
use App\Models\Auth\Role;
use App\Transformers\Auth\Role as Transformer;
use Dingo\Api\Routing\Helpers;

class Roles extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->collect();

        return $this->response->paginator($roles, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Role  $role
     * @return \Dingo\Api\Http\Response
     */
    public function show(Role $role)
    {
        return $this->response->item($role, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create($request->input());

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->get('permissions'));
        }

        return $this->response->created(url('api/roles/'.$role->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $role
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Role $role, Request $request)
    {
        $role->update($request->all());

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->get('permissions'));
        }

        return $this->response->item($role->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return $this->response->noContent();
    }
}
