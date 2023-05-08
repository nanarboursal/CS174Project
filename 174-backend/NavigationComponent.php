<html>
    <head>
        <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
        <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
        <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    </head>
    <div id="root"></div>
    <script type="text/babel">
        const Cookies = window.Cookies;

        const Navigation = () => {
            const user = Cookies.get('session');

            function handleLogout() {
                fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/logout.php')
                    .then(response => response.text())
                    .then(data => console.log(data))
                    .catch(error => console.error(error));
                Cookies.remove('session', { path: '/' });
                window.location.href = "/login";
            }

            return (
                <Navbar color="light" light expand="md">
                    <NavbarBrand href="/">Organizer</NavbarBrand>
                    {user && (
                        <Nav className="ml-auto" navbar>
                            <NavItem>
                                <NavLink href="/calendar">Calendar</NavLink>
                            </NavItem>
                            <NavItem>
                                <NavLink href="/">Todo List</NavLink>
                            </NavItem>
                            <NavItem>
                                <button onClick={handleLogout}>Logout</button>
                            </NavItem>
                        </Nav>
                    )}
                    {!user && (
                        <Nav className="ml-auto" navbar>
                            <NavItem>
                                <NavLink href="/login">Login</NavLink>
                            </NavItem>
                            <NavItem>
                                <NavLink href="/register">Sign up</NavLink>
                            </NavItem>
                        </Nav>
                    )}
                </Navbar>
            );
        }
    </script>
    <!-- <script>
        ReactDOM.render(element, document.getElementById("root"));
    </script> -->
</html>