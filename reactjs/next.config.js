const nextRuntimeDotenv = require('next-runtime-dotenv');
const withSass = require('@zeit/next-sass');

// Makes certain variables accessible only at runtime visible for the application.
const withConfig = nextRuntimeDotenv({
  public: ['BACKEND_URL', 'TESTS_BACKEND_URL'],
  server: ['HTTP_AUTH_USER', 'HTTP_AUTH_PASS'],
});

module.exports = withConfig(withSass());
