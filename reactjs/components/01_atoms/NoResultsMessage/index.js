import React from 'react';
import PropTypes from 'prop-types';

const NoResultsMessage = ({ message }) => (
  <div className="no-results-message">
    {message}
  </div>
);

NoResultsMessage.propTypes = {
  message: PropTypes.string.isRequired,
};

export default NoResultsMessage;
