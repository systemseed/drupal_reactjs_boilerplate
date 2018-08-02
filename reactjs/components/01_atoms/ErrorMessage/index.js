import React from 'react';
import PropTypes from 'prop-types';
import { Link } from '../../../routes';

const ErrorMessage = ({ statusCode }) => (
  <div className="error-message">
    Oops, {statusCode} error. <br />
    <Link to="/">To the Homepage</Link>
  </div>
);

ErrorMessage.propTypes = {
  statusCode: PropTypes.number.isRequired,
};

export default ErrorMessage;
