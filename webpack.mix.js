const mix = require('laravel-mix');
const tailwindcss = require("tailwindcss");

mix
    .vue()
    .js('resources/js/app.js', 'public/js')
    .postCss('resources/fonts/icons.css', 'public/css')
    .postCss('resources/css/app.css', 'public/css', [], [
        tailwindcss,
    ])
    .webpackConfig({
        watchOptions: {
            aggregateTimeout: 300,
            poll: 1000
        }
    });
