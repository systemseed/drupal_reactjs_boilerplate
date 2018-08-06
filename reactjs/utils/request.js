import superAgent from 'superagent';
import superagentDefaults from 'superagent-defaults';
import superagentJsonapify from 'superagent-jsonapify';
import superagentPrefix from 'superagent-prefix';

// Define variable for Drupal domain to interact with from the frontend.
let backendUrl = '';

// If the application is running on platform.sh, then we need to assign
// a placeholder value as the backend url to get it replaced during the
// deploy phase (see .platform.app.yaml hooks).
if (process.env.PLATFORM_PROJECT) {
  backendUrl = 'http://platformsh.placeholder';
} else {
  // Otherwise local environment should always have link to the backend.
  backendUrl = `http://${process.env.BACKEND_HOST}`;
}

const prefix = superagentPrefix(backendUrl);

// Get superagent object & make it ready to set some default values.
// Make superagent library know how to handle JSON API responses &
// provide magic methods for working with responses based on JSON API
// specification.
const superagent = superagentDefaults(superagentJsonapify(superAgent));

// Pre-define superagent client.
superagent
// Set the right URL prefix so that the request always
// gets to the right place despite of being executed on
// the server or client level.
  .use(prefix)
  // Default headers for JSON API integration in Drupal.
  .set('Content-Type', 'application/vnd.api+json')
  .set('Accept', 'application/vnd.api+json');

// If the current environment includes http auth variables, then include them
// as a custom header into the request.
if (process.env.HTTP_AUTH_USER && process.env.HTTP_AUTH_PASS) {
  superagent.set('HTTP-Auth', `${process.env.HTTP_AUTH_USER}:${process.env.HTTP_AUTH_PASS}`);
}

export default superagent;
