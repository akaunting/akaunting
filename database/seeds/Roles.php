<?php
namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Roles extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

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
                'common-dashboards' => 'c,r,u,d',
                'common-import' => 'c',
                'common-items' => 'c,r,u,d',
                'common-notifications' => 'c,r,u,d',
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-uploads' => 'r,d',
                'common-widgets' => 'c,r,u,d',
                'purchases-bills' => 'c,r,u,d',
                'purchases-payments' => 'c,r,u,d',
                'purchases-vendors' => 'c,r,u,d',
                'sales-customers' => 'c,r,u,d',
                'sales-invoices' => 'c,r,u,d',
                'sales-revenues' => 'c,r,u,d',
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
                'widgets-account-balance' => 'r',
                'widgets-cash-flow' => 'r',
                'widgets-expenses-by-category' => 'r',
                'widgets-income-by-category' => 'r',
                'widgets-latest-expenses' => 'r',
                'widgets-latest-income' => 'r',
                'widgets-total-expenses' => 'r',
                'widgets-total-income' => 'r',
                'widgets-total-profit' => 'r',
            ],
            'manager' => [
                'admin-panel' => 'r',
                'auth-profile' => 'r,u',
                'banking-accounts' => 'c,r,u,d',
                'banking-reconciliations' => 'c,r,u,d',
                'banking-transactions' => 'c,r,u,d',
                'banking-transfers' => 'c,r,u,d',
                'common-companies' => 'c,r,u,d',
                'common-dashboards' => 'c,r,u,d',
                'common-import' => 'c',
                'common-items' => 'c,r,u,d',
                'common-notifications' => 'c,r,u,d',
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-uploads' => 'r',
                'common-widgets' => 'c,r,u,d',
                'purchases-bills' => 'c,r,u,d',
                'purchases-payments' => 'c,r,u,d',
                'purchases-vendors' => 'c,r,u,d',
                'sales-customers' => 'c,r,u,d',
                'sales-invoices' => 'c,r,u,d',
                'sales-revenues' => 'c,r,u,d',
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
                'widgets-account-balance' => 'r',
                'widgets-cash-flow' => 'r',
                'widgets-expenses-by-category' => 'r',
                'widgets-income-by-category' => 'r',
                'widgets-latest-expenses' => 'r',
                'widgets-latest-income' => 'r',
                'widgets-total-expenses' => 'r',
                'widgets-total-income' => 'r',
                'widgets-total-profit' => 'r',
            ],
            'customer' => [
                'client-portal' => 'r',
                'portal-invoices' => 'r,u',
                'portal-payments' => 'r,u',
                'portal-profile' => 'r,u',
            ]
        ];

        return $rows;
    }

    private function actions()
    {
        return collect([
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
        ]);
    }

    private function create()
    {
        $roles = $this->roles();

        $actions_map = $this->actions();

        foreach ($roles as $role_name => $permissions) {
            $this->command->info('Creating Role ' . Str::title($role_name));

            $role = Role::firstOrCreate([
                'name' => $role_name,
            ], [
                'display_name' => Str::title($role_name),
                'description' => Str::title($role_name),
            ]);

            foreach ($permissions as $page => $action_list) {
                $actions = explode(',', $action_list);

                foreach ($actions as $short_action) {
                    $action = $actions_map->get($short_action);

                    $display_name = Str::title($action . ' ' . str_replace('-', ' ', $page));

                    $this->command->info('Creating Permission ' . $display_name);

                    $permission = Permission::firstOrCreate([
                        'name' => $action . '-' . $page,
                    ], [
                        'display_name' => $display_name,
                        'description' => $display_name,
                    ]);

                    if ($role->hasPermission($permission->name)) {
                        $this->command->info($role_name . ': ' . $display_name . ' already exist');

                        continue;
                    }

                    $role->attachPermission($permission);
                }
            }
        }
    }
}
