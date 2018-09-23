import React from 'react';
import { NavbarToggler, NavItem, Nav, Collapse } from 'reactstrap';
import { Link } from '../../../routes';

class MainMenu extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      isOpen: false,
    };

    this.toggle = this.toggle.bind(this);
  }

  toggle() {
    this.setState(state => ({
      isOpen: !state.isOpen,
    }));
  }

  render() {
    const { isOpen } = this.state;
    return (
      <>
        <NavbarToggler onClick={this.toggle} />
        <Collapse isOpen={isOpen} navbar>
          <Nav className="ml-auto" navbar>

            <NavItem>
              <Link to="/">
                <a className="nav-link">Home</a>
              </Link>
            </NavItem>

          </Nav>
        </Collapse>
      </>
    );
  }
}

export default MainMenu;
