<html>
    <head>
        <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
        <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
        <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.0/js.cookie.min.js"></script>
        <link rel="stylesheet" href="Login.css">
        <link rel="stylesheet" href="Navigation.css">
    </head>
    <div id="root"></div>
    <script type="text/babel">
        const { useState } = React;
        const Cookies = window.Cookies;

        function Login() {
            const [username, setUsername] = useState('');
            const [password, setPassword] = useState('');
            const [errorMessage, setErrorMessage] = useState('');

            const handleSubmit = (event) => {
                event.preventDefault();
                fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                    console.log('successful login');
                    Cookies.set('session', username, { path: 'http://cos-cs106.science.sjsu.edu/~013879866/code' });
                    window.location.href = "http://cos-cs106.science.sjsu.edu/~013879866/tester/test2.php"; // change once todo is up
                    } else {
                    setErrorMessage(data.message);
                    }
                })
                .catch(error => console.error(error));
            }

            return (
                <div id='ctn'>
                    <div className='form'>
                        <form onSubmit={handleSubmit}>
                            <label>
                                Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" value={username} onChange={e => setUsername(e.target.value)} />
                            </label>
                            <label>
                                Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="password" value={password} onChange={e => setPassword(e.target.value)} />
                            </label>
                            <button type="submit">Login</button>
                            {errorMessage && <div>{errorMessage}</div>}
                        </form>
                    </div>
                </div>
            );
        }
        // ReactDOM.render(<Login />, document.getElementById("root"));
    </script>
    <script type="text/babel">
        const Navigation = () => {
            const user = Cookies.get('session');

            function handleLogout() {
                fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/logout.php')
                    .then(response => response.text())
                    .then(data => console.log(data))
                    .catch(error => console.error(error));
                Cookies.remove('session', { path: 'http://cos-cs106.science.sjsu.edu/~013879866/code' });
                window.location.href = "http://cos-cs106.science.sjsu.edu/~013879866/code/LoginComponent.php";
            }

            return (
                <div id='nav'>
                    <div>
                        <a href="http://cos-cs106.science.sjsu.edu/~013879866/tester/test2.php" class='button'>Organizer</a>
                    </div>
                    
                    {user && (
                        <div>
                            <a href="http://cos-cs106.science.sjsu.edu/~013879866/tester/test2.php" class='button'>Calendar</a>
                            <a href="http://cos-cs106.science.sjsu.edu/~013879866/tester/test2.php" class='button'>To-do List</a>
                            <button className='button' onClick={handleLogout}>Logout</button>
                        </div>
                    )}
                    {!user && (
                        <div>
                            <a href="http://cos-cs106.science.sjsu.edu/~013879866/tester/test2.php" class='button'>Sign up</a>
                            <a href="http://cos-cs106.science.sjsu.edu/~013879866/code/LoginComponent.php" class='button'>Login</a>
                        </div>
                    )}
                </div>
            );
        }
    </script>
    <script type="text/babel">
        ReactDOM.render(<><Navigation /><Login /></>, document.getElementById("root"));
    </script>
</html>