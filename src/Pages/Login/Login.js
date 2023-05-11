import React, { useState } from 'react';
import Cookies from 'js-cookie';
import './Login.css';

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
          Cookies.set('session', username, { path: '/' });
          window.location.href = "/";
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

export default Login;
