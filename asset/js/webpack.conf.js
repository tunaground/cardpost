const webpack = require('webpack');
module.exports = {
    mode: 'development',
    entry: {
        index: './src/index.js',
    },
    output: {
        path: './dist',
        filename: '[name].js',
        publicPath: '/',
    },
    module: {
        rules: [{
            test: /\.js$/,
            loader: 'babel-loader',
            options: {
                presets: [
                    [
                        'env', {
                        targets: {
                            node: 'current'
                        },
                        modules: 'false'
                    }
                    ],
                ],
            },
            exclude: ['/node_modules'],
        }],
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        })
    ],
    optimization: {},
    resolve: {
        modules: ['node_modules'],
        extensions: ['.js', '.json', '.jsx', '.css'],
    },
};