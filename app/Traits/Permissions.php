<?php

namespace App\Traits;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Support\Str;

trait Permissions
{
    public function getActionsMap()
    {
        return [
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
        ];
    }

    public function attachPermissions($roles)
    {
        $actions_map = collect($this->getActionsMap());

        foreach ($roles as $role_name => $permissions) {
            $role_display_name = Str::title($role_name);

            $role = Role::firstOrCreate([
                'name' => $role_name,
            ], [
                'display_name' => $role_display_name,
                'description' => $role_display_name,
            ]);

            foreach ($permissions as $page => $action_list) {
                $actions = explode(',', $action_list);

                foreach ($actions as $short_action) {
                    $action = $actions_map->get($short_action);

                    $display_name = Str::title($action . ' ' . str_replace('-', ' ', $page));

                    $permission = Permission::firstOrCreate([
                        'name' => $action . '-' . $page,
                    ], [
                        'display_name' => $display_name,
                        'description' => $display_name,
                    ]);

                    if ($role->hasPermission($permission->name)) {
                        continue;
                    }

                    $role->attachPermission($permission);
                }
            }
        }
    }

    public function detachPermissions($roles)
    {
        foreach ($roles as $role_name => $permissions) {
            $role = Role::where('name', $role_name)->first();

            if (empty($role)) {
                continue;
            }

            foreach ($permissions as $permission_name) {
                $permission = Permission::where('name', $permission_name)->first();

                if (empty($permission)) {
                    continue;
                }

                $role->detachPermission($permission);
                $permission->delete();
            }
        }
    }

    public function updatePermissionNames($permissions)
    {
        $actions = $this->getActionsMap();

        foreach ($permissions as $old => $new) {
            foreach ($actions as $action) {
                $old_name = $action . '-' . $old;

                $permission = Permission::where('name', $old_name)->first();

                if (empty($permission)) {
                    continue;
                }

                $new_name = $action . '-' . $new;
                $new_display_name = Str::title(str_replace('-', ' ', $new_name));

                $permission->update([
                    'name' => $new_name,
                    'display_name' => $new_display_name,
                ]);
            }
        }
    }
}
