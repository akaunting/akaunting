<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use Illuminate\Database\Seeder;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        Model::unguard();

        $this->create($this->roles(), $this->map());

        Model::reguard();
    }

    private function roles()
    {
        $rows = [
            'admin' => [
                'admin-panel' => 'r',
                'api' => 'r',
                'auth-permissions' => 'c,r,u,d',
                'auth-profile' => 'r,u',
                'auth-roles' => 'c,r,u,d',
                'auth-users' => 'c,r,u,d',
                'banking-accounts' => 'c,r,u,d',
                'banking-reconciliations' => 'c,r,u,d',
                'banking-transactions' => 'c,r,u,d',
                'banking-transfers' => 'c,r,u,d',
                'common-companies' => 'c,r,u,d',
                'common-import' => 'c',
                'common-items' => 'c,r,u,d',
                'common-notifications' => 'c,r,u,d',
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-uploads' => 'r,d',
                'common-widgets' => 'c,r,u,d',
                'expenses-bills' => 'c,r,u,d',
                'expenses-payments' => 'c,r,u,d',
                'expenses-vendors' => 'c,r,u,d',
                'incomes-customers' => 'c,r,u,d',
                'incomes-invoices' => 'c,r,u,d',
                'incomes-revenues' => 'c,r,u,d',
                'install-updates' => 'r,u',
                'modules-api-key' => 'c,u',
                'modules-home' => 'r',
                'modules-item' => 'c,r,u,d',
                'modules-my' => 'r',
                'modules-tiles' => 'r',
                'notifications' => 'r,u',
                'reports-expense-summary' => 'r',
                'reports-income-summary' => 'r',
                'reports-income-expense-summary' => 'r',
                'reports-profit-loss' => 'r',
                'reports-tax-summary' => 'r',
                'settings-appearance' => 'r,u',
                'settings-categories' => 'c,r,u,d',
                'settings-company' => 'r',
                'settings-currencies' => 'c,r,u,d',
                'settings-defaults' => 'r',
                'settings-email' => 'r',
                'settings-invoice' => 'r',
                'settings-localisation' => 'r',
                'settings-modules' => 'r,u',
                'settings-settings' => 'r,u',
                'settings-schedule' => 'r',
                'settings-taxes' => 'c,r,u,d',
                'wizard-companies' => 'c,r,u',
                'wizard-currencies' => 'c,r,u',
                'wizard-finish' => 'c,r,u',
                'wizard-taxes' => 'c,r,u',
            ],
            'manager' => [
                'admin-panel' => 'r',
                'auth-profile' => 'r,u',
                'banking-accounts' => 'c,r,u,d',
                'banking-reconciliations' => 'c,r,u,d',
                'banking-transactions' => 'c,r,u,d',
                'banking-transfers' => 'c,r,u,d',
                'common-companies' => 'c,r,u,d',
                'common-import' => 'c',
                'common-items' => 'c,r,u,d',
                'common-notifications' => 'c,r,u,d',
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-uploads' => 'r',
                'common-widgets' => 'r',
                'expenses-bills' => 'c,r,u,d',
                'expenses-payments' => 'c,r,u,d',
                'expenses-vendors' => 'c,r,u,d',
                'incomes-customers' => 'c,r,u,d',
                'incomes-invoices' => 'c,r,u,d',
                'incomes-revenues' => 'c,r,u,d',
                'install-updates' => 'r,u',
                'notifications' => 'r,u',
                'reports-expense-summary' => 'r',
                'reports-income-summary' => 'r',
                'reports-income-expense-summary' => 'r',
                'reports-profit-loss' => 'r',
                'reports-tax-summary' => 'r',
                'settings-categories' => 'c,r,u,d',
                'settings-company' => 'r',
                'settings-currencies' => 'c,r,u,d',
                'settings-defaults' => 'r',
                'settings-email' => 'r',
                'settings-invoice' => 'r',
                'settings-localisation' => 'r',
                'settings-modules' => 'r,u',
                'settings-settings' => 'r,u',
                'settings-schedule' => 'r',
                'settings-taxes' => 'c,r,u,d',
            ],
            'customer' => [
                'client-portal' => 'r',
                'portal-invoices' => 'r,u',
                'portal-payments' => 'r,u',
                'portal-transactions' => 'r',
                'portal-profile' => 'r,u',
            ],
        ];

        return $rows;
    }

    private function map()
    {
        $rows = [
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete'
        ];

        return $rows;
    }

    private function create($roles, $map)
    {
        $mapPermission = collect($map);

        foreach ($roles as $key => $modules) {
            // Create a new role
            $role = Role::create([
                'name' => $key,
                'display_name' => ucwords(str_replace("_", " ", $key)),
                'description' => ucwords(str_replace("_", " ", $key))
            ]);

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {
                $permissions = explode(',', $value);

                foreach ($permissions as $p => $perm) {
                    $permissionValue = $mapPermission->get($perm);

                    $moduleName = ucwords(str_replace("-", " ", $module));

                    $permission = Permission::firstOrCreate([
                        'name' => $permissionValue . '-' . $module,
                        'display_name' => ucfirst($permissionValue) . ' ' . $moduleName,
                        'description' => ucfirst($permissionValue) . ' ' . $moduleName,
                    ]);

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $moduleName);

                    if (!$role->hasPermission($permission->name)) {
                        $role->attachPermission($permission);
                    } else {
                        $this->command->info($key . ': ' . $p . ' ' . $permissionValue . ' already exist');
                    }
                }
            }
        }
    }
}
