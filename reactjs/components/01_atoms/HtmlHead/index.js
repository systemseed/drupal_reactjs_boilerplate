import React from 'react';
import Head from 'next/head';
import Package from '../../../package';
import inlineCSS from '../../../styles/theme.scss';

const HtmlHead = () => {
  let stylesheets;

  if (process.env.NODE_ENV === 'production') {
    // In production, serve pre-built CSS file from /assets/{version}/main.css
    const pathToCSS = `/assets/${Package.version}/main.css`;
    stylesheets = <link rel="stylesheet" type="text/css" href={pathToCSS} />;
  } else {
    // eslint-disable-next-line react/no-danger
    stylesheets = <style dangerouslySetInnerHTML={{ __html: inlineCSS }} />;
  }

  return (
    <Head>
      <title>SystemSeed&apos;s Decoupled boilerplate</title>
      <meta charSet="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <meta httpEquiv="X-UA-Compatible" content="IE=edge" />
      <link rel="shortcut icon" href="/static/favicon.ico" type="image/vnd.microsoft.icon" />
      {stylesheets}
    </Head>
  );
};

export default HtmlHead;
