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

//mix.js('resources/assets/js/views/**/*.js', 'public/js')

mix
    .setPublicPath('public/')
    .webpackConfig({
        output: {
            publicPath: 'public/js/',
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
    .js('resources/assets/js/views/auth/common.js', 'public/js/auth/common.min.js')
    .js('resources/assets/js/views/auth/users.js', 'public/js/auth/users.min.js')

    // Banking
    .js('resources/assets/js/views/banking/accounts.js', 'public/js/banking/accounts.min.js')
    .js('resources/assets/js/views/banking/transactions.js', 'public/js/banking/transactions.min.js')
    .js('resources/assets/js/views/banking/transfers.js', 'public/js/banking/transfers.min.js')
    .js('resources/assets/js/views/banking/reconciliations.js', 'public/js/banking/reconciliations.min.js')

    // Common
    .js('resources/assets/js/views/common/contacts.js', 'public/js/common/contacts.min.js')
    .js('resources/assets/js/views/common/companies.js', 'public/js/common/companies.min.js')
    .js('resources/assets/js/views/common/dashboards.js', 'public/js/common/dashboards.min.js')
    .js('resources/assets/js/views/common/documents.js', 'public/js/common/documents.min.js')
    .js('resources/assets/js/views/common/imports.js', 'public/js/common/imports.min.js')
    .js('resources/assets/js/views/common/items.js', 'public/js/common/items.min.js')
    .js('resources/assets/js/views/common/reports.js', 'public/js/common/reports.min.js')

    // Install
    .js('resources/assets/js/install.js', 'public/js/install.min.js')
    .js('resources/assets/js/views/install/update.js', 'public/js/install/update.min.js')

    //Wizard
    .js('resources/assets/js/wizard.js', 'public/js/wizard/wizard.min.js')

    // Modules
    .js('resources/assets/js/views/modules/apps.js', 'public/js/modules/apps.min.js')

    // Portal
    .js('resources/assets/js/views/portal/apps.js', 'public/js/portal/apps.min.js')

    // Settings
    .js('resources/assets/js/views/settings/categories.js', 'public/js/settings/categories.min.js')
    .js('resources/assets/js/views/settings/currencies.js', 'public/js/settings/currencies.min.js')
    .js('resources/assets/js/views/settings/settings.js', 'public/js/settings/settings.min.js')
    .js('resources/assets/js/views/settings/taxes.js', 'public/js/settings/taxes.min.js')

    .vue()

    .postCss('resources/assets/sass/app.css', 'public/css', [
        require('tailwindcss')
    ])
    .tailwind('./tailwind.config.js')

    if (mix.inProduction()) {
        mix.version()
    }
