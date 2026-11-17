const mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        symlinks: false
    },
    externals: {
        jquery: 'jQuery',
    }
})

mix.options({
    processCssUrls: false
})

mix.setPublicPath('..')

mix.copy('node_modules/@glidejs/glide/dist/glide.min.js', '../js/glide.js')

mix.js('assets/js/index.js', 'js/lite-slider.js')
    .sass('assets/sass/lite-slider.scss', 'css')
    .sass('assets/sass/lite-slider-theme.scss', 'css')

mix.sass('assets/sass/form.scss', 'blocks/xw_lite_slider/auto.css')

mix.disableNotifications();

// Disable mix-manifest.json
// @see https://github.com/JeffreyWay/laravel-mix/issues/580
Mix.manifest.refresh = _ => void 0;
