/**
 * Transforms backend user data into frontend readable one.
 *
 * @param data
 * @returns {{}}
 */
export const user = data => ({
  name: data.name,
  id: data.uid,
});
