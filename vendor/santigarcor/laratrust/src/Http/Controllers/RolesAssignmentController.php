<?php

namespace Laratrust\Http\Controllers;

use Laratrust\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class RolesAssignmentController
{
    protected $rolesModel;
    protected $permissionModel;
    protected $assignPermissions;

    public function __construct()
    {
        $this->rolesModel = Config::get('laratrust.models.role');
        $this->permissionModel = Config::get('laratrust.models.permission');
        $this->assignPermissions = Config::get('laratrust.panel.assign_permissions_to_user');
    }

    public function index(Request $request)
    {
        $modelsKeys = array_keys(Config::get('laratrust.user_models'));
        $modelKey = $request->get('model') ?? $modelsKeys[0] ?? null;
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            abort(404);
        }

        return View::make('laratrust::panel.roles-assignment.index', [
            'models' => $modelsKeys,
            'modelKey' => $modelKey,
            'users' => $userModel::query()
                ->withCount(['roles', 'permissions'])
                ->simplePaginate(10),
        ]);
    }

    public function edit(Request $request, $modelId)
    {
        $modelKey = $request->get('model');
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            Session::flash('laratrust-error', 'Model was not specified in the request');
            return redirect(route('laratrust.roles-assignment.index'));
        }

        $user = $userModel::query()
            ->with(['roles:id,name', 'permissions:id,name'])
            ->findOrFail($modelId);

        $roles = $this->rolesModel::orderBy('name')->get(['id', 'name', 'display_name'])
            ->map(function ($role) use ($user) {
                $role->assigned = $user->roles
                ->pluck('id')
                    ->contains($role->id);
                $role->isRemovable = Helper::roleIsRemovable($role);

                return $role;
            });
        if ($this->assignPermissions) {
            $permissions = $this->permissionModel::orderBy('name')
                ->get(['id', 'name', 'display_name'])
                ->map(function ($permission) use ($user) {
                    $permission->assigned = $user->permissions
                        ->pluck('id')
                        ->contains($permission->id);

                    return $permission;
                });
        }


        return View::make('laratrust::panel.roles-assignment.edit', [
            'modelKey' => $modelKey,
            'roles' => $roles,
            'permissions' => $this->assignPermissions ? $permissions : null,
            'user' => $user,
        ]);
    }

    public function update(Request $request, $modelId)
    {
        $modelKey = $request->get('model');
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            Session::flash('laratrust-error', 'Model was not specified in the request');
            return redirect()->back();
        }

        $user = $userModel::findOrFail($modelId);
        $user->syncRoles($request->get('roles') ?? []);
        if ($this->assignPermissions) {
            $user->syncPermissions($request->get('permissions') ?? []);
        }

        Session::flash('laratrust-success', 'Roles and permissions assigned successfully');
        return redirect(route('laratrust.roles-assignment.index', ['model' => $modelKey]));
    }
}
