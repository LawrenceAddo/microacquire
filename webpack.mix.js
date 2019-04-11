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

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/pages/seller-profile-edit.js', 'public/js/pages')
   .js('resources/js/pages/seller-listing.js', 'public/js/pages')
   .js('resources/js/pages/buyer-profile-edit.js', 'public/js/pages')
   .js('resources/js/pages/buyer-profile-view.js', 'public/js/pages')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/home.scss', 'public/css');
   
