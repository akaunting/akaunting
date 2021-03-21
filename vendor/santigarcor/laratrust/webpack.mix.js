let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.setPublicPath('public')
  .postCss('resources/css/styles.css', 'public/laratrust.css', [
    require('tailwindcss'),
  ])
  .version()
  .copy('docs/.vuepress/public/logo.png', 'public/img/logo.png');