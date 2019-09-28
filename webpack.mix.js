let mix = require('laravel-mix')

mix
    .disableNotifications()
    .setPublicPath('dist')
    .js('resources/js/tool.js', 'js')
