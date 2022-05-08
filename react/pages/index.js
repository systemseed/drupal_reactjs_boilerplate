import React from 'react';
import PropTypes from 'prop-types';
import * as articleApi from '../api/article';
import Link from "next/link"

class HomePage extends React.Component {
  static async getInitialProps() {
    let initialProps = {
      articles: [],
      statusCode: 200,
    };

    try {
      // Load all articles from the backend.
      initialProps = await articleApi.getAll();
    } catch (e) {
      // Pass status code as internal properly. It is being checked inside of
      // render() method of _app.js.
      console.log(e);
      initialProps.statusCode = 500;
    }

    return initialProps;
  }

  render() {
    const { articles } = this.props;
    
    return (
      <div>
            Home page is working!<br /><br />
            List of articles from Drupal:<br />
        <ul>
          {articles.map((article) => <li key={article.id}>
            <Link href={{ pathname: "/article/" + article.id }}>
              <a>{article.title}</a>
            </Link> {console.log(article)}
            { article.editable ? <Link className="edit-link" href={{ pathname: "/article/" + article.id + "/edit"}}> [Edit]</Link> : ''}
            </li>)
          }
        </ul>
      </div>
    );
  }
}

HomePage.propTypes = {
  articles: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.string,
    title: PropTypes.string,
    body: PropTypes.string,
    editable: PropTypes.bool
  })),
};

HomePage.defaultProps = {
  articles: [],
};

export default HomePage;
