import React from 'react';
import Cookies from 'js-cookie';
import './Navigation.css';

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
        <div id='nav'>
            <div>
                <a href="/" class='button'>A+ Planner</a>
            </div>

            {user && (
                 <div>
                 <a href="/calendar" class='button'>Calendar</a>
                 <a href="/" class='button'>To-do List</a>
                 <button className='button' onClick={handleLogout}>Logout</button>
             </div>
         )}
         {!user && (
            <div>
            <a href="/register" class='button'>Sign up</a>
            <a href="/login" class='button'>Login</a>
        </div>
    )}
    </div>
    );
}

export default Navigation;