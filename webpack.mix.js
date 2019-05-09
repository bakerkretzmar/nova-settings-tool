let mix = require('laravel-mix')

mix.disableSuccessNotifications()

mix.setPublicPath('dist')
    .js('resources/js/tool.js', 'js')
