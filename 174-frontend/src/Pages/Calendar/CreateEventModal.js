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
    console.log(duedate);

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
            console.log(data);
            if (data.success) {
              console.log('successfully added event');
              window.location.href = "/calendar";
            } else {
              console.log(data.message);
            }
          })
          .catch(error => console.error(error));

    // Add the new event to the events array
    // setEvents((prevEvents) => [...prevEvents, newEvent]);

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
      <form onSubmit={handleSubmit}>
        <label>
          Title:
          <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} />
        </label>
        <button type="submit">Create Event</button>
        <button onChange={(e) => onClose()}>Cancel</button>
      </form>
    </ReactModal>
  );
};

export default CreateEventModal;