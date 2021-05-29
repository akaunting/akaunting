<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Auth\User as Request;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;
use App\Models\Auth\User;
use App\Transformers\Auth\User as Transformer;

class Users extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $users = User::with('companies', 'permissions', 'roles')->collect();

        return $this->response->paginator($users, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // Check if we're querying by id or email
        if (is_numeric($id)) {
            $user = User::with('companies', 'permissions', 'roles')->find($id);
        } else {
            $user = User::with('companies', 'permissions', 'roles')->where('email', $id)->first();
        }

        return $this->item($user, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->dispatch(new CreateUser($request));

        return $this->response->created(route('api.users.show', $user->id), $this->item($user, new Transformer()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $user
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(User $user, Request $request)
    {
        $user = $this->dispatch(new UpdateUser($user, $request));

        return $this->item($user->fresh(), new Transformer());
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  User  $user
     * @return \Dingo\Api\Http\Response
     */
    public function enable(User $user)
    {
        $user = $this->dispatch(new UpdateUser($user, request()->merge(['enabled' => 1])));

        return $this->item($user->fresh(), new Transformer());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  User  $user
     * @return \Dingo\Api\Http\Response
     */
    public function disable(User $user)
    {
        $user = $this->dispatch(new UpdateUser($user, request()->merge(['enabled' => 0])));

        return $this->item($user->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $this->dispatch(new DeleteUser($user));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
