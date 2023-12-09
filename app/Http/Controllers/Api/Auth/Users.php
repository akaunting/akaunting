<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Auth\User as Request;
use App\Http\Resources\Auth\User as Resource;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;

class Users extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = user_model_class()::with('companies', 'media', 'permissions', 'roles')->isNotCustomer()->collect();

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
        $model_class = user_model_class();

        // Check if we're querying by id or email
        if (is_numeric($id)) {
            $user = $model_class::with('companies', 'permissions', 'roles')->find($id);
        } else {
            $user = $model_class::with('companies', 'permissions', 'roles')->where('email', $id)->first();
        }

        if (! $user instanceof $model_class) {
            return $this->errorInternal('No query results for model [' . $model_class . '] ' . $id);
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
     * @param  $user_id
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($user_id, Request $request)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        $user = $this->dispatch(new UpdateUser($user, $request));

        return new Resource($user->fresh());
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  $user_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        $user = $this->dispatch(new UpdateUser($user, request()->merge(['enabled' => 1])));

        return new Resource($user->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  $user_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        $user = $this->dispatch(new UpdateUser($user, request()->merge(['enabled' => 0])));

        return new Resource($user->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $user_id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        try {
            $this->dispatch(new DeleteUser($user));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
