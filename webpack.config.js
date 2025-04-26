const Encore = require('@symfony/webpack-encore');
const path = require('path');
const webpack = require('webpack');

Encore
    .setOutputPath('public_html/build/')
    .setPublicPath('/build')
    .addEntry('app', './frontend/src/main.js')
    .enableVueLoader()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader();

const config = Encore.getWebpackConfig();

// Add alias for '@' to resolve the src folder
config.resolve.alias = {
    '@': path.resolve(__dirname, 'frontend/src'),
    'process/browser': require.resolve('process/browser'),
};

// Add fallback for process
config.resolve.fallback = {
    process: require.resolve('process/browser')
};

// Provide process globally
config.plugins.push(
    new webpack.ProvidePlugin({
        process: 'process/browser'
    })
);
config.plugins.push(
    new webpack.DefinePlugin({
        '__VUE_OPTIONS_API__': JSON.stringify(true),
        '__VUE_PROD_DEVTOOLS__': JSON.stringify(false),
        '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': JSON.stringify(false)
    }));
module.exports = config;
