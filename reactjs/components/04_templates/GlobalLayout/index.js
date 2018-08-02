import React, { Fragment } from 'react';
import PropTypes from 'prop-types';
import GlobalHeader from '../../03_organisms/GlobalHeader';
import GlobalFooter from '../../03_organisms/GlobalFooter';

const GlobalLayout = ({ children }) => (
  <Fragment>
    <GlobalHeader />
    <div className="content">
      {children}
    </div>
    <GlobalFooter />
  </Fragment>
);

GlobalLayout.propTypes = {
  children: PropTypes.node.isRequired,
};

export default GlobalLayout;
