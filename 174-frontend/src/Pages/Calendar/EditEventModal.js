import React, { useState, useEffect } from 'react';
import Cookies from 'js-cookie';
import ReactModal from "react-modal";
import './CreateEventModal.css';
import './EditEventModal.css';

const EditEventModal = ({ isOpen, onClose, oldtitle }) => {
    const [title, setTitle] = useState("");
    const [date, setDate] = useState("");
    const user = Cookies.get('session');

    function handleDateUpdate(e) {
        e.preventDefault();

        fetch('http://cos-cs106.science.sjsu.edu/~014155765/code/edit-event-date.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: user, title: oldtitle, duedate: date})
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    console.log('successfully updated date');
                    window.location.href = "/calendar";
                } else {
                    console.log(data.message);
                }
            })
            .catch(error => console.error(error));
    }

    function handleTitleUpdate(e) {
        e.preventDefault();

        fetch('http://cos-cs106.science.sjsu.edu/~014155765/code/edit-event-title.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: user, title: oldtitle, newtitle: title})
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    console.log('successfully updated title');
                    window.location.href = "/calendar";
                } else {
                    console.log(data.message);
                }
            })
            .catch(error => console.error(error));
    }

    function handleDelete(e) {
        e.preventDefault();

        fetch('http://cos-cs106.science.sjsu.edu/~014155765/code/edit-event-delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: user, title: oldtitle})
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    console.log('successfully removed event');
                    window.location.href = "/calendar";
                } else {
                    console.log(data.message);
                }
            })
            .catch(error => console.error(error));
    }

    function handleExport(e) {
        e.preventDefault();

        fetch('http://cos-cs106.science.sjsu.edu/~014155765/code/edit-event-export.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: user, title: oldtitle})
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    console.log('successfully exported event');
                    alert('Successfully exported event')
                    window.location.href = "/calendar";
                } else {
                    console.log(data.message);
                    alert('Event already in todo list');
                }
            })
            .catch(error => console.error(error));
    }

  return (
    <ReactModal 
        className="edit-modal" 
        overlayClassName="modal-overlay"
        isOpen={isOpen} 
        onRequestClose={onClose}>
      <h2>Edit Event</h2>
      <p>{oldtitle}</p>
      <form className='edit-form'>
        <div className='title-update'>
            <label>
            Title:&nbsp;&nbsp;&nbsp;
            <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} />
            </label>
            <button onClick={handleTitleUpdate}>Update title</button>
        </div>
        <div className='date-update'>
            <label>
            Date (yyyy-mm-dd):&nbsp;&nbsp;&nbsp;
            <input type="text" value={date} onChange={(e) => setDate(e.target.value)} />
            </label>
            <button onClick={handleDateUpdate}>Update date</button>
        </div>
        <div className='other-btn'>
            <button onClick={handleDelete}>Delete event</button>
            <button onClick={handleExport}>Export to todo list</button>
            <button onClick={(e) => onClose()}>Cancel</button>
        </div>
      </form>
    </ReactModal>
  );
};

export default EditEventModal;