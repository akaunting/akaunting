const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

//mix.js('resources/assets/js/views/**/*.js', 'public/js')

mix
    // Auth
    .js('resources/assets/js/views/auth/forgot.js', 'public/js/auth')
    .js('resources/assets/js/views/auth/login.js', 'public/js/auth')
    .js('resources/assets/js/views/auth/permissions.js', 'public/js/auth')
    .js('resources/assets/js/views/auth/reset.js', 'public/js/auth')
    .js('resources/assets/js/views/auth/roles.js', 'public/js/auth')
    .js('resources/assets/js/views/auth/users.js', 'public/js/auth')

    // Banking
    .js('resources/assets/js/views/banking/accounts.js', 'public/js/banking')
    .js('resources/assets/js/views/banking/transactions.js', 'public/js/banking')
    .js('resources/assets/js/views/banking/transfers.js', 'public/js/banking')
    .js('resources/assets/js/views/banking/reconciliations.js', 'public/js/banking')

    // Common
    .js('resources/assets/js/views/common/items.js', 'public/js/common')
    .js('resources/assets/js/views/common/companies.js', 'public/js/common')
    .js('resources/assets/js/views/common/dashboard.js', 'public/js/common')
    .js('resources/assets/js/views/common/reports.js', 'public/js/common')
    .js('resources/assets/js/views/common/search.js', 'public/js/common')

    // Expenses
    .js('resources/assets/js/views/expenses/bills.js', 'public/js/expenses')
    .js('resources/assets/js/views/expenses/payments.js', 'public/js/expenses')
    .js('resources/assets/js/views/expenses/vendors.js', 'public/js/expenses')

    // Incomes
    .js('resources/assets/js/views/incomes/invoices.js', 'public/js/incomes')
    .js('resources/assets/js/views/incomes/revenues.js', 'public/js/incomes')
    .js('resources/assets/js/views/incomes/customers.js', 'public/js/incomes')

    // Install
    .js('resources/assets/js/install.js', 'public/js')

    // Modules
    .js('resources/assets/js/views/modules/item.js', 'public/js/modules')

    // Portal
    .js('resources/assets/js/views/portal/dashboard.js', 'public/js/portal')
    .js('resources/assets/js/views/portal/invoices.js', 'public/js/portal')
    .js('resources/assets/js/views/portal/payments.js', 'public/js/portal')
    .js('resources/assets/js/views/portal/profile.js', 'public/js/portal')
    .js('resources/assets/js/views/portal/transactions.js', 'public/js/portal')

    // Settings
    .js('resources/assets/js/views/settings/categories.js', 'public/js/settings')
    .js('resources/assets/js/views/settings/currencies.js', 'public/js/settings')
    .js('resources/assets/js/views/settings/modules.js', 'public/js/settings')
    .js('resources/assets/js/views/settings/settings.js', 'public/js/settings')
    .js('resources/assets/js/views/settings/taxes.js', 'public/js/settings')


    // Wizard
    .js('resources/assets/js/views/wizard/company.js', 'public/js/wizard')
    .js('resources/assets/js/views/wizard/currencies.js', 'public/js/wizard')
    .js('resources/assets/js/views/wizard/taxes.js', 'public/js/wizard')

    .sass('resources/assets/sass/argon.scss', 'public/css');
