import React from 'react';
import { BrowserRouter as Router, Routes, Route, HashRouter } from 'react-router-dom';
import Navigation from './Components/Navigation/Navigation';
import Calendar from './Pages/Calendar/Calendar';
import TodoList from './Pages/TodoList/TodoList';
import Login from './Pages/Login/Login';
import Register from './Pages/Register/Register';

const App = () => {
  return (
    <HashRouter
      // basename={"/app"}
    >
      <div className='App'>
        <Navigation />
        <Routes>
          <Route path="/" element={<TodoList />} exact />
          <Route path="/calendar" element={<Calendar />} exact />
          <Route path="/login" element={<Login />} exact />
          <Route path="/register" element={<Register />} exact />
        </Routes>
      </div>
    </HashRouter>
  );
}

export default App;
