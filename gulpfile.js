var elixir = require('laravel-elixir');

require('laravel-elixir-vue');

var jsPath = 'resources/assets/js/vendor/';
var lessPath = 'resources/assets/less/vendor/';
var cssAdminPath = 'resources/assets/css/admin/';

var adminltepath = 'vendor/almasaeed2010/adminlte/dist/';


elixir(function(mix) {

    mix.sass('admin.scss', null, null, {
        includePaths: [ 'node_modules' ]
    });
    mix.sass('main.scss', null, null, {
        includePaths: [ 'node_modules' ]
    });

    mix.webpack('admin.js');
    mix.webpack('main.js');


    mix.copy('node_modules/admin-lte/dist/js/app.min.js', 'public/js/admin-lte.js');

    mix.version([
        'css/admin.css', 'js/admin.js', 'css/main.css', 'js/main.js'
        ]);
});
