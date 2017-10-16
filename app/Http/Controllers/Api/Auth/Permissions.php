<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\Permission as Request;
use App\Models\Auth\Permission;
use App\Transformers\Auth\Permission as Transformer;
use Dingo\Api\Routing\Helpers;

class Permissions extends ApiController
{
    use Helpers;

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
        return $this->response->item($permission, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $permission = Permission::create($request->all());

        return $this->response->created(url('api/permissions/'.$permission->id));
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
        $permission->update($request->all());

        return $this->response->item($permission->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Permission  $permission
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return $this->response->noContent();
    }
}
