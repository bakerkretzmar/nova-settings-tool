let mix = require('laravel-mix')

mix.disableSuccessNotifications()

mix.setPublicPath('dist')
   .js('resources/js/tool.js', 'js')
   .sass('resources/sass/tool.scss', 'css')
