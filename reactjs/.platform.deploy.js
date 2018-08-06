// This script is being used in .platform.app.yaml file on the deploy hook.
// It is needed to figure out the URL of the backend application.
if (process.env.PLATFORM_PROJECT) {
  // Load platform.sh config.
  const config = require('platformsh').config(); // eslint-disable-line global-require
  for (let url in config.routes) { // eslint-disable-line no-restricted-syntax, guard-for-in
    const route = config.routes[url];
    if (route.original_url === 'https://admin.{default}/') {
      // Remove "/" from the end of the url.
      console.log(url.substring(0, url.length - 1));
    }
  }
}
