import request from '../utils/request';
import * as transforms from '../utils/transforms';

/**
 * Returns users list from backend.
 *
 * @returns {Promise<any>}
 */
export const getAll = () => new Promise((resolve, reject) => {
  request
    .get('/api/users')
    .query({
      'fields[users]': 'id,name',
      'filter[no_anon][condition][path]': 'uid',
      'filter[no_anon][condition][operator]': '>',
      'filter[no_anon][condition][value]': '0',
    })
    // Tell superagent to consider any valid Drupal response as successful.
    // Later we can capture error codes if needed.
    .ok(resp => resp.statusCode)
    .then((response) => {
      resolve({
        // eslint-disable-next-line max-len
        users: response.statusCode === 200 ? response.body.data.map(user => transforms.user(user)) : [],
        statusCode: response.statusCode,
      });
    })
    .catch((error) => {
      // eslint-disable-next-line no-console
      console.error('Could not fetch the users.', error);
      reject(error);
    });
});
