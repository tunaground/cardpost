const webpack = require('webpack');
const path = require('path');
module.exports = {
	mode: 'development',
	entry: {
		index: ['./src/index.js']
	},
    output: {
        path: path.resolve(__dirname, '../public/js'),
        filename: '[name].js',
        publicPath: '/js',
    },
    module: {
        rules: [{
            test: /\.js$/,
            loader: 'babel-loader',
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
