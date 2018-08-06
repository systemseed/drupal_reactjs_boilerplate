const globImporter = require('node-sass-glob-importer');
const webpack = require('webpack');

module.exports = {
  webpack: (config) => {
    config.module.rules.push(
      {
        test: /\.(css|scss)/,
        loader: 'emit-file-loader',
        options: {
          name: 'dist/[path][name].[ext]',
        },
      },
      {
        test: /\.css$/,
        use: ['babel-loader', 'raw-loader'],
      },
      {
        test: /\.s(a|c)ss$/,
        use: [
          'babel-loader',
          'raw-loader',
          {
            loader: 'sass-loader',
            options: {
              importer: globImporter(),
            },
          },
        ],
      },
    );

    // Make environment variables (.env or on platform.sh) available on the
    // client applications. Otherwise set default values.
    config.plugins.push(new webpack.EnvironmentPlugin({
      PLATFORM_PROJECT: '',
      BACKEND_HOST: '',
    }));

    return config;
  },
};
