const path = require('path');
const webpack = require('webpack');

module.exports = {
  mode: 'production',
  entry: './src/tezos-wordpress-plugin.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'tezos-wp-plugin.bundle.js',
  },
  resolve: {
    modules: [path.resolve(__dirname, 'node_modules')],
    extensions: ['.js', '.json'],
    alias: {
      stream: 'stream-browserify'
    }
  },
  plugins: [
    new webpack.ProvidePlugin({
      Buffer: ['buffer', 'Buffer'],
    }),
  ],
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
          },
        },
      },
    ],
  },
};

