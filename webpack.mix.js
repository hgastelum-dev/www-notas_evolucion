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

/*mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        
    ]);*/

// bootstrap/bootswatch v5.2.2
mix.copy("node_modules/bootswatch/dist/darkly/bootstrap.min.css", "public/lib/css");
mix.copy("node_modules/bootstrap/dist/js/bootstrap.bundle.min.js", "public/lib/js");
mix.copy("node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map", "public/lib/js");

// fontawesome v6.2.0
mix.copy("node_modules/@fortawesome", "public/lib/css");

// sweetalert2
mix.copy("node_modules/sweetalert2", "public/lib/js/sweetalert2");

// datatables v1.12.1
mix.copy("node_modules/datatables.net", "public/lib/js/datatables.net");
mix.copy("node_modules/datatables.net-bs5", "public/lib/js/datatables.net-bs5");

// jquery
mix.copy("node_modules/jquery", "public/lib/js/jquery");

// moment
mix.copy("node_modules/moment", "public/lib/js/moment");

mix.copy("node_modules/summernote", "public/lib/js/summernote");