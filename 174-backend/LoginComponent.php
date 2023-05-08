<html>
    <head>
        <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
        <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
        <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
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
                <form onSubmit={handleSubmit}>
                    <label>
                        Username:
                        <input type="text" value={username} onChange={e => setUsername(e.target.value)} />
                    </label>
                    <label>
                        Password:
                        <input type="password" value={password} onChange={e => setPassword(e.target.value)} />
                    </label>
                    <button type="submit">Login</button>
                    {errorMessage && <div>{errorMessage}</div>}
                </form>
            );
        }
        ReactDOM.render(<Login />, document.getElementById("root"));
    </script>
    <!-- <script>
        ReactDOM.render(element, document.getElementById("root"));
    </script> -->
</html>