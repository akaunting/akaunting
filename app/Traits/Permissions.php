<?php

namespace App\Traits;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Utilities\Reports;
use App\Utilities\Widgets;
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

    public function attachPermissionsByRoleNames($roles)
    {
        foreach ($roles as $role_name => $permissions) {
            $role = $this->createRole($role_name);

            foreach ($permissions as $id => $permission) {
                $this->attachPermissionsByAction($role, $id, $permission);
            }
        }
    }

    public function attachPermissionsToAdminRoles($permissions)
    {
        $this->attachPermissionsToAllRoles($permissions, 'read-admin-panel');
    }

    public function attachPermissionsToPortalRoles($permissions)
    {
        $this->attachPermissionsToAllRoles($permissions, 'read-client-portal');
    }

    public function attachPermissionsToAllRoles($permissions, $require = 'read-admin-panel')
    {
        $this->getRoles($require)->each(function ($role) use ($permissions) {
            foreach ($permissions as $id => $permission) {
                if ($this->isActionList($permission)) {
                    $this->attachPermissionsByAction($role, $id, $permission);

                    continue;
                }

                $this->attachPermission($role, $permission);
            }
        });
    }

    public function detachPermissionsByRoleNames($roles)
    {
        foreach ($roles as $role_name => $permissions) {
            $role = Role::where('name', $role_name)->first();

            if (empty($role)) {
                continue;
            }

            foreach ($permissions as $permission_name) {
                $this->detachPermission($role, $permission_name);
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
                $new_display_name = $this->getPermissionDisplayName($new_name);

                $permission->update([
                    'name' => $new_name,
                    'display_name' => $new_display_name,
                ]);
            }
        }
    }

    public function attachDefaultModulePermissions($module, $require = 'read-admin-panel')
    {
        $this->attachModuleReportPermissions($module, $require);

        $this->attachModuleWidgetPermissions($module, $require);

        $this->attachModuleSettingPermissions($module, $require);
    }

    public function attachModuleReportPermissions($module, $require = 'read-admin-panel')
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (empty($module->get('reports'))) {
            return;
        }

        $permissions = [];

        foreach ($module->get('reports') as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $permissions[] = $this->createModuleReportPermission($module, $class);
        }

        $this->attachPermissionsToAllRoles($permissions, $require);
    }

    public function attachModuleWidgetPermissions($module, $require = 'read-admin-panel')
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (empty($module->get('widgets'))) {
            return;
        }

        $permissions = [];

        foreach ($module->get('widgets') as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $permissions[] = $this->createModuleWidgetPermission($module, $class);
        }

        $this->attachPermissionsToAllRoles($permissions, $require);
    }

    public function attachModuleSettingPermissions($module, $require = 'read-admin-panel')
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (empty($module->get('settings'))) {
            return;
        }

        $permissions = [];

        $permissions[] = $this->createModuleSettingPermission($module, 'read');
        $permissions[] = $this->createModuleSettingPermission($module, 'update');

        $this->attachPermissionsToAllRoles($permissions, $require);
    }

    public function createModuleReportPermission($module, $class)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (!class_exists($class)) {
            return;
        }

        $name = Reports::getPermission($class);
        $display_name = 'Read ' . $module->getName() . ' Reports ' . Reports::getDefaultName($class);

        return $this->createPermission($name, $display_name);
    }

    public function createModuleWidgetPermission($module, $class)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (!class_exists($class)) {
            return;
        }

        $name = Widgets::getPermission($class);
        $display_name = 'Read ' . $module->getName() . ' Widgets ' . Widgets::getDefaultName($class);

        return $this->createPermission($name, $display_name);
    }

    public function createModuleSettingPermission($module, $action)
    {
        return $this->createModuleControllerPermission($module, $action, 'settings');
    }

    public function createModuleControllerPermission($module, $action, $controller)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        $name = $action . '-' . $module->getAlias() . '-' . $controller;
        $display_name = Str::title($action) . ' ' . $module->getName() . ' ' . Str::title($controller);

        return $this->createPermission($name, $display_name);
    }

    public function createRole($name, $display_name = null, $description = null)
    {
        $display_name = $display_name ?? Str::title($name);

        return Role::firstOrCreate([
            'name' => $name,
        ], [
            'display_name' => $display_name,
            'description' => $description ?? $display_name,
        ]);
    }

    public function createPermission($name, $display_name = null, $description = null)
    {
        $display_name = $display_name ?? $this->getPermissionDisplayName($name);

        return Permission::firstOrCreate([
            'name' => $name,
        ], [
            'display_name' => $display_name,
            'description' => $description ?? $display_name,
        ]);
    }

    public function attachPermission($role, $permission)
    {
        if (is_string($permission)) {
            $permission = $this->createPermission($permission);
        }

        if ($role->hasPermission($permission->name)) {
            return;
        }

        $role->attachPermission($permission);
    }

    public function detachPermission($role, $permission)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (empty($role)) {
            return;
        }

        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (empty($permission)) {
            return;
        }

        $role->detachPermission($permission);
        $permission->delete();
    }

    public function isActionList($permission)
    {
        if (!is_string($permission)) {
            return false;
        }

        // c || c,r,u,d
        return (Str::length($permission) == 1) || Str::contains($permission, ',');
    }

    public function attachPermissionsByAction($role, $page, $action_list)
    {
        $actions_map = collect($this->getActionsMap());

        $actions = explode(',', $action_list);

        foreach ($actions as $short_action) {
            $action = $actions_map->get($short_action);

            $name = $action . '-' . $page;

            $this->attachPermission($role, $name);
        }
    }

    public function getPermissionDisplayName($name)
    {
        if (!empty($this->alias)) {
            $name = str_replace($this->alias, '{Module Placeholder}', $name);
        }

        $name = Str::title(str_replace('-', ' ', $name));

        if (!empty($this->alias)) {
            $name = str_replace('{Module Placeholder}', module($this->alias)->getName(), $name);
        }

        return $name;
    }

    public function getRoles($require = 'read-admin-panel')
    {
        return Role::all()->filter(function ($role) use ($require) {
            return $require ? $role->hasPermission($require) : true;
        });
    }
}
