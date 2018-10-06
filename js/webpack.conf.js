const webpack = require('webpack');
const path = require('path');
module.exports = {
	mode: 'development',
	entry: {
		index: ['./src/index.js'],
        write: ['./src/write.js'],
        trace: ['./src/trace.js']
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
    optimization: {},
    resolve: {
        modules: ['node_modules'],
        extensions: ['.js', '.json', '.jsx', '.css'],
    },
};
