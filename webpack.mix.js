let mix = require('laravel-mix')

mix.extend('quill', webpackConfig => {
    const { rules } = webpackConfig.module;

    // 1. Exclude quill's SVG icons from existing rules
    rules.filter(rule => /svg/.test(rule.test.toString()))
        .forEach(rule => rule.exclude = /quill/);

    // 2. Instead, use html-loader for quill's SVG icons
    rules.unshift({
        test: /\.svg$/,
        include: [path.resolve('./node_modules/quill/assets')],
        loaders: [{ loader: 'html-loader', options: { minimize: true } }]
    });

    // 3. Don't exclude quill from babel-loader rule
    rules.filter(rule => rule.exclude && rule.exclude.toString() === "/(node_modules|bower_components)/")
        .forEach(rule => rule.exclude = /node_modules\/(?!(quill)\/).*/);
});

mix
    .disableNotifications()
    .setPublicPath('dist')
    .quill()
    .js('resources/js/tool.js', 'js')
