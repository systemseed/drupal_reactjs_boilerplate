const globImporter = require('node-sass-glob-importer');
const nextRuntimeDotenv = require('next-runtime-dotenv');

const withConfig = nextRuntimeDotenv({
  public: ['BACKEND_URL'],
  server: ['HTTP_AUTH_USER', 'HTTP_AUTH_PASS'],
});

module.exports = withConfig({
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

    return config;
  },
});
