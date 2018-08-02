import React from 'react';
import PropTypes from 'prop-types';
import { Pagination, PaginationItem, PaginationLink, Container, Row, Col } from 'reactstrap';
import { withRouter } from 'next/router';
import { Router } from '../../../../routes';

class MiniPager extends React.Component {
  constructor(props) {
    super(props);

    this.handlePrevPageClick = this.handlePrevPageClick.bind(this);
    this.handleNextPageClick = this.handleNextPageClick.bind(this);
  }

  handlePrevPageClick() {
    const { router, hasPrevPage } = this.props;
    const currentPage = router.query.page ? parseInt(router.query.page, 10) : 0;

    if (!hasPrevPage) {
      return;
    }

    router.query.page = currentPage - 1;
    Router.push(router);
  }

  handleNextPageClick() {
    const { router, hasNextPage } = this.props;
    const currentPage = router.query.page ? parseInt(router.query.page, 10) : 0;

    if (!hasNextPage) {
      return;
    }

    router.query.page = currentPage + 1;
    Router.push(router);
  }

  render() {
    const { hasPrevPage, hasNextPage } = this.props;

    // Don't render the pager if both next & prev page links can't be displayed.
    if (!hasPrevPage && !hasNextPage) {
      return null;
    }

    return (
      <Container>
        <Row>
          <Col>
            <Pagination className="mini-pager">
              <PaginationItem disabled={!hasPrevPage} onClick={this.handlePrevPageClick}>
                <PaginationLink previous />
              </PaginationItem>
              <PaginationItem disabled={!hasNextPage} onClick={this.handleNextPageClick}>
                <PaginationLink next />
              </PaginationItem>
            </Pagination>
          </Col>
        </Row>
      </Container>
    );
  }
}

MiniPager.propTypes = {
  hasPrevPage: PropTypes.bool,
  hasNextPage: PropTypes.bool,
  router: PropTypes.shape,
};

MiniPager.defaultProps = {
  hasPrevPage: true,
  hasNextPage: true,
  router: {},
};

export default withRouter(MiniPager);
