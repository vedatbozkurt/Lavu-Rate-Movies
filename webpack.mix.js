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

mix.js([
    'resources/js/backend/app.js',
    'public/theme/dist/js/adminlte.min.js'
    ], 'public/js/backend.js')
 .sass('resources/sass/app.scss', 'public/css/')
 .styles([
    'public/theme/dist/css/adminlte.min.css',
    ], 'public/css/backend.css')
 .version();

mix.js('resources/js/frontend/app.js', 'public/js/frontend.js')
   .version();
