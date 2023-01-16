<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Auth\User as Request;
use App\Http\Resources\Auth\User as Resource;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;
use App\Models\Auth\User;

class Users extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::with('companies', 'permissions', 'roles')->collect();

        return Resource::collection($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Check if we're querying by id or email
        if (is_numeric($id)) {
            $user = User::with('companies', 'permissions', 'roles')->find($id);
        } else {
            $user = User::with('companies', 'permissions', 'roles')->where('email', $id)->first();
        }

        if (! $user instanceof User) {
            return $this->errorInternal('No query results for model [' . User::class . '] ' . $id);
        }

        return new Resource($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $this->dispatch(new CreateUser($request));

        return $this->created(route('api.users.show', $user->id), new Resource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $user
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(User $user, Request $request)
    {
        $user = $this->dispatch(new UpdateUser($user, $request));

        return new Resource($user->fresh());
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  User  $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(User $user)
    {
        $user = $this->dispatch(new UpdateUser($user, request()->merge(['enabled' => 1])));

        return new Resource($user->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  User  $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(User $user)
    {
        $user = $this->dispatch(new UpdateUser($user, request()->merge(['enabled' => 0])));

        return new Resource($user->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $this->dispatch(new DeleteUser($user));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
