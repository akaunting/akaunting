<?php
namespace Database\Seeds;

use App\Abstracts\Model;
use App\Traits\Permissions as Helper;
use Illuminate\Database\Seeder;

class Permissions extends Seeder
{
    use Helper;

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

    private function create()
    {
        $rows = [
            'admin' => [
                'admin-panel' => 'r',
                'api' => 'r',
                'auth-profile' => 'r,u',
                'auth-users' => 'c,r,u,d',
                'banking-accounts' => 'c,r,u,d',
                'banking-reconciliations' => 'c,r,u,d',
                'banking-transactions' => 'c,r,u,d',
                'banking-transfers' => 'c,r,u,d',
                'common-companies' => 'c,r,u,d',
                'common-dashboards' => 'c,r,u,d',
                'common-import' => 'c',
                'common-items' => 'c,r,u,d',
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-uploads' => 'r,d',
                'common-widgets' => 'c,r,u,d',
                'purchases-bills' => 'c,r,u,d',
                'purchases-vendors' => 'c,r,u,d',
                'sales-customers' => 'c,r,u,d',
                'sales-invoices' => 'c,r,u,d',
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
                'settings-categories' => 'c,r,u,d',
                'settings-company' => 'r,u',
                'settings-currencies' => 'c,r,u,d',
                'settings-defaults' => 'r,u',
                'settings-email' => 'r,u',
                'settings-email-templates' => 'r,u',
                'settings-invoice' => 'r,u',
                'settings-localisation' => 'r,u',
                'settings-modules' => 'r,u',
                'settings-schedule' => 'r,u',
                'settings-taxes' => 'c,r,u,d',
                'widgets-account-balance' => 'r',
                'widgets-bank-feeds' => 'r',
                'widgets-cash-flow' => 'r',
                'widgets-currencies' => 'r',
                'widgets-expenses-by-category' => 'r',
                'widgets-payables' => 'r',
                'widgets-profit-loss' => 'r',
                'widgets-receivables' => 'r',
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
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-uploads' => 'r',
                'common-widgets' => 'c,r,u,d',
                'purchases-bills' => 'c,r,u,d',
                'purchases-vendors' => 'c,r,u,d',
                'sales-customers' => 'c,r,u,d',
                'sales-invoices' => 'c,r,u,d',
                'install-updates' => 'r,u',
                'notifications' => 'r,u',
                'reports-expense-summary' => 'r',
                'reports-income-summary' => 'r',
                'reports-income-expense-summary' => 'r',
                'reports-profit-loss' => 'r',
                'reports-tax-summary' => 'r',
                'settings-categories' => 'c,r,u,d',
                'settings-company' => 'r,u',
                'settings-currencies' => 'c,r,u,d',
                'settings-defaults' => 'r,u',
                'settings-email' => 'r,u',
                'settings-email-templates' => 'r,u',
                'settings-invoice' => 'r,u',
                'settings-localisation' => 'r,u',
                'settings-modules' => 'r,u',
                'settings-schedule' => 'r,u',
                'settings-taxes' => 'c,r,u,d',
                'widgets-account-balance' => 'r',
                'widgets-bank-feeds' => 'r',
                'widgets-cash-flow' => 'r',
                'widgets-currencies' => 'r',
                'widgets-expenses-by-category' => 'r',
                'widgets-payables' => 'r',
                'widgets-profit-loss' => 'r',
                'widgets-receivables' => 'r',
            ],
            'customer' => [
                'client-portal' => 'r',
                'portal-invoices' => 'r,u',
                'portal-payments' => 'r,u',
                'portal-profile' => 'r,u',
            ],
            'accountant' => [
                'admin-panel' => 'r',
                'api' => 'r',
                'auth-profile' => 'r,u',
                'common-dashboards' => 'r',
                'common-items' => 'r',
                'common-reports' => 'r',
                'purchases-bills' => 'r',
                'purchases-vendors' => 'r',
                'sales-customers' => 'r',
                'sales-invoices' => 'r',
                'banking-accounts' => 'r',
                'banking-reconciliations' => 'r',
                'banking-transactions' => 'r',
                'banking-transfers' => 'r',
                'reports-expense-summary' => 'r',
                'reports-income-summary' => 'r',
                'reports-income-expense-summary' => 'r',
                'reports-profit-loss' => 'r',
                'reports-tax-summary' => 'r',
                'modules-home' => 'r',
                'modules-item' => 'r',
                'modules-my' => 'r',
                'modules-tiles' => 'r',
                'widgets-account-balance' => 'r',
                'widgets-bank-feeds' => 'r',
                'widgets-cash-flow' => 'r',
                'widgets-currencies' => 'r',
                'widgets-expenses-by-category' => 'r',
                'widgets-payables' => 'r',
                'widgets-profit-loss' => 'r',
                'widgets-receivables' => 'r',
            ],
        ];

        $this->attachPermissionsByRoleNames($rows);
    }
}
