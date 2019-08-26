import * as transforms from '../utils/transforms';
import request from '../utils/request';

/**
 * Returns articles list from backend.
 *
 * @returns {Promise<any>}
 */
export const getAll = () => new Promise((resolve, reject) => {
  request
    .get('/api/articles')
    .query({
      'fields[node--article]': 'id',
    })
    // Tell superagent to consider any valid Drupal response as successful.
    // Later we can capture error codes if needed.
    .ok((resp) => resp.statusCode)
    .then((response) => {
      resolve({
        // eslint-disable-next-line max-len
        articles: response.statusCode === 200 ? response.body.data.map((article) => transforms.article(article)) : [],
        statusCode: response.statusCode,
      });
    })
    .catch((error) => {
      // eslint-disable-next-line no-console
      console.error('Could not fetch the articles.', error);
      reject(error);
    });
});
