const mix = require('laravel-mix');
const { join } = require('path');

mix.setPublicPath('dist').disableNotifications();

mix.vue({ version: 3 })
    .js('resources/js/tool.js', 'js')
    .css('resources/css/tool.css', 'css', [require('tailwindcss'), require('autoprefixer')])
    .webpackConfig({
        externals: {
            vue: 'Vue',
        },
        output: {
            uniqueName: 'bakerkretzmar/nova-settings-tool',
        },
    });
