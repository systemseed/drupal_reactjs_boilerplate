import React from 'react';
import App, { Container } from 'next/app';
import { Provider } from 'react-redux';
import withRedux from 'next-redux-wrapper';
import withReduxSaga from 'next-redux-saga';
import HtmlHead from '../components/01_atoms/HtmlHead';
import configureStore from '../store/store';
import ErrorMessage from '../components/01_atoms/ErrorMessage';
import SiteLayout from '../components/04_templates/GlobalLayout';
import superagent from '../utils/request';
import '../components/01_atoms/PageProgressBar'; // Beautiful page transition indicator.

class Application extends App {
  static async getInitialProps({ Component, res, ctx }) {
    const initialProps = {
      isServer: !!ctx.req,
    };

    // If server request, then add cookies to the superagent object.
    if (ctx.req) {
      superagent.set('Cookie', ctx.req.headers.cookie);
    }

    // Add superagent to the global initial props object.
    initialProps.superagent = superagent;

    // Call to getInitialProps() from the Page component.
    if (Component.getInitialProps) {
      const childInitialProps = await Component.getInitialProps({
        ...initialProps,
        ...ctx,
      });

      // In case of Server Side rendering we want the server to throw the
      // correct error code.
      if (res && initialProps.statusCode) {
        res.statusCode = initialProps.statusCode;
      }

      return {
        ...initialProps,
        ...childInitialProps,
      };
    }

    return initialProps;
  }

  render() {
    const { Component, store, ...pageProps } = this.props;
    const statusCode = pageProps.statusCode || 200;
    return (
      <Container>
        <Provider store={store}>
          <>
            <HtmlHead />
            <SiteLayout>
              {statusCode === 200
              && <Component {...pageProps} />
              }
              {statusCode !== 200
              && <ErrorMessage statusCode={statusCode} />
              }
            </SiteLayout>
          </>
        </Provider>
      </Container>
    );
  }
}

export default withRedux(configureStore)(withReduxSaga(Application));
