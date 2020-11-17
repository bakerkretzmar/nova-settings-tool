const mix = require('laravel-mix');

mix.disableNotifications();

mix.setPublicPath('dist')
    .js('resources/js/tool.js', 'js');
