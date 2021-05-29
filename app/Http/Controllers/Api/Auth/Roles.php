<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Auth\Role as Request;
use App\Models\Auth\Role;
use App\Jobs\Auth\CreateRole;
use App\Jobs\Auth\DeleteRole;
use App\Jobs\Auth\UpdateRole;
use App\Transformers\Auth\Role as Transformer;

class Roles extends ApiController
{
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
        return $this->item($role, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $role = $this->dispatch(new CreateRole($request));

        return $this->response->created(route('api.roles.show', $role->id), $this->item($role, new Transformer()));
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
        $role = $this->dispatch(new UpdateRole($role, $request));

        return $this->item($role->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            $this->dispatch(new DeleteRole($role));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
