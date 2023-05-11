import React, { useState, useEffect } from 'react';
import Cookies from 'js-cookie';
import ReactModal from "react-modal";
import moment from 'moment';
import './CreateEventModal.css';

const CreateEventModal = ({ isOpen, onClose, date }) => {
  const [title, setTitle] = useState("");

  const handleSubmit = (e) => {
    e.preventDefault();

    const user = Cookies.get('session');
    const duedate = moment(date).format('YYYY-MM-DD');

    // Create new event with the selected date and title
    fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/add-event.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ username: user, title: title, duedate: duedate})
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              console.log('successfully added event');
              window.location.href = "/calendar";
            } else {
              console.log(data.message);
            }
          })
          .catch(error => console.error(error));

    // Close the modal
    onClose();
  };

  const datestr = moment(date).format('MMMM D, YYYY');

  return (
    <ReactModal 
        className="create-modal" 
        overlayClassName="modal-overlay"
        isOpen={isOpen} 
        onRequestClose={onClose}>
      <h2>Add Event</h2>
      <p>{datestr}</p>
      <form className='form-ctn' onSubmit={handleSubmit}>
        <label>
          Title:&nbsp;&nbsp;&nbsp;
          <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} />
        </label>
        <div className='btn-ctn'>
          <button type="submit">Create Event</button>
          <button onClick={(e) => onClose()}>Cancel</button>
        </div>
      </form>
    </ReactModal>
  );
};

export default CreateEventModal;