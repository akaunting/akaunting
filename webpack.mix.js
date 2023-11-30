const mix = require('laravel-mix');
require('laravel-mix-tailwind');

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

//mix.js('resources/assets/js/views/**/*.js', '/js')

mix
    .setPublicPath('/')
    .webpackConfig({
        output: {
            publicPath: '/js/',
            filename: '[name].js',
            chunkFilename: '[name].js',
        },
        stats: {
            children: true
        },
    })
    .options({
        terser: {
            extractComments: false,
        }
    })

    // Auth
    .js('resources/assets/js/views/auth/common.js', '/js/auth/common.min.js')
    .js('resources/assets/js/views/auth/users.js', '/js/auth/users.min.js')

    // Banking
    .js('resources/assets/js/views/banking/accounts.js', '/js/banking/accounts.min.js')
    .js('resources/assets/js/views/banking/transactions.js', '/js/banking/transactions.min.js')
    .js('resources/assets/js/views/banking/transfers.js', '/js/banking/transfers.min.js')
    .js('resources/assets/js/views/banking/reconciliations.js', '/js/banking/reconciliations.min.js')

    // Common
    .js('resources/assets/js/views/common/contacts.js', '/js/common/contacts.min.js')
    .js('resources/assets/js/views/common/companies.js', '/js/common/companies.min.js')
    .js('resources/assets/js/views/common/dashboards.js', '/js/common/dashboards.min.js')
    .js('resources/assets/js/views/common/documents.js', '/js/common/documents.min.js')
    .js('resources/assets/js/views/common/imports.js', '/js/common/imports.min.js')
    .js('resources/assets/js/views/common/items.js', '/js/common/items.min.js')
    .js('resources/assets/js/views/common/reports.js', '/js/common/reports.min.js')

    // Install
    .js('resources/assets/js/install.js', '/js/install.min.js')
    .js('resources/assets/js/views/install/update.js', '/js/install/update.min.js')

    //Wizard
    .js('resources/assets/js/wizard.js', '/js/wizard/wizard.min.js')

    // Modules
    .js('resources/assets/js/views/modules/apps.js', '/js/modules/apps.min.js')

    // Portal
    .js('resources/assets/js/views/portal/apps.js', '/js/portal/apps.min.js')

    // Settings
    .js('resources/assets/js/views/settings/categories.js', '/js/settings/categories.min.js')
    .js('resources/assets/js/views/settings/currencies.js', '/js/settings/currencies.min.js')
    .js('resources/assets/js/views/settings/settings.js', '/js/settings/settings.min.js')
    .js('resources/assets/js/views/settings/taxes.js', '/js/settings/taxes.min.js')

    .vue()

    .postCss('resources/assets/sass/app.css', '/css', [
        require('tailwindcss')
    ])
    .tailwind('./tailwind.config.js')

    if (mix.inProduction()) {
        mix.version()
    }
