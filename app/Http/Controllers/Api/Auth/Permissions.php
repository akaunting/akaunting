<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Auth\Permission as Request;
use App\Models\Auth\Permission;
use App\Jobs\Auth\CreatePermission;
use App\Jobs\Auth\DeletePermission;
use App\Jobs\Auth\UpdatePermission;
use App\Transformers\Auth\Permission as Transformer;

class Permissions extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $permissions = Permission::collect();

        return $this->response->paginator($permissions, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Permission  $permission
     * @return \Dingo\Api\Http\Response
     */
    public function show(Permission $permission)
    {
        return $this->item($permission, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $permission = $this->dispatch(new CreatePermission($request));

        return $this->response->created(route('api.permissions.show', $permission->id), $this->item($permission, new Transformer()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $permission
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Permission $permission, Request $request)
    {
        $permission = $this->dispatch(new UpdatePermission($permission, $request));

        return $this->item($permission->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Permission  $permission
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Permission $permission)
    {
        try {
            $this->dispatch(new DeletePermission($permission));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
