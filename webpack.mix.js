const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/frontend.js', 'public/js/frontend.min.js').vue()
.sass('public/frontend/handyman.scss', 'public/css/frontend.min.css', []);

mix.js('resources/js/backend-bundle.js', 'public/js/backend-bundle.min.js')
    .sass('resources/scss/backend-bundle.scss','public/css/backend-bundle.min.css')
    .sass('public/scss/backend.scss', 'public/css')
    .options({
        processCssUrls: false
    });
