/**
 * Transforms backend article data into frontend readable one.
 *
 * @param data
 * @returns {{}}
 */
export const article = (data) => ({
  title: data.title,
  id: data.id,
});
