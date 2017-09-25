const { mix } = require('laravel-mix');

mix
   .js('resources/assets/js/main.js', 'public/js')
   .js('resources/assets/js/admin.js', 'public/js')
   .sass('resources/assets/sass/main.scss', 'public/css', {
        includePaths: [ 'node_modules' ]
    })
   .sass('resources/assets/sass/admin.scss', 'public/css', {
        includePaths: [ 'node_modules' ]
    })
   .copy('node_modules/admin-lte/dist/js/app.min.js', 'public/js/admin-lte.js')

if (mix.inProduction()) {
    mix.version();
}
