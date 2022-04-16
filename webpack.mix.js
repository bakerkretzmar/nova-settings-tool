const mix = require('laravel-mix');
const { join } = require('path');

mix.setPublicPath('dist').disableNotifications();

mix.vue()
    .js('resources/js/tool.js', 'js')
    .webpackConfig({
        externals: {
            vue: 'Vue',
        },
        output: {
            uniqueName: 'bakerkretzmar/nova-settings-tool',
        },
    });
