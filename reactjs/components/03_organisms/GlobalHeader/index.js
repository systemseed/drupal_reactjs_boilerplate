import './_style.scss';
import React from 'react';
import { Container, Row, Col, Navbar } from 'reactstrap';
import Logo from '../../01_atoms/Logo';
import MainMenu from '../../01_atoms/MainMenu';

const GlobalHeader = () => (
  <header>
    <Container>
      <Row>
        <Col>
          <Navbar light expand="md" className="bg-faded">
            <Logo />
            <MainMenu />
          </Navbar>
        </Col>
      </Row>
    </Container>
  </header>
);

export default GlobalHeader;
