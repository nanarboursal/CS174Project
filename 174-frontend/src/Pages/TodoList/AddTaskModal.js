import React, { useState, useEffect } from 'react';
import Cookies from 'js-cookie';
import ReactModal from "react-modal";
import moment from 'moment';
import './add-task-modal.css';

const AddTaskModal = ({ isOpen, onClose }) => {
    const [description, setDescription] = useState("");
    const [dueDate, setDueDate] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault();

        const user = Cookies.get('session');
        console.log("Submit was pressed on the modal!");

        // Create new task with the selected description and due date
        fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/add-task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: user, description: description, duedate: dueDate })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('successfully added task');
                    window.location.href = "/";
                } else {
                    console.log(data.message);
                }
            })
            .catch(error => console.error(error));

        // Close the modal
        onClose();
    };

    return (
        <ReactModal
            className="task-add-modal"
            overlayClassName="modal-overlay"
            isOpen={isOpen}
            onRequestClose={onClose}>
            <h2>Add Task</h2>
            <form className='add-task-form' onSubmit={handleSubmit}>
                <div>
                    <label>
                        Description:&nbsp;&nbsp;&nbsp;
                        <input type="text" value={description} onChange={(e) => setDescription(e.target.value)} />
                    </label>
                </div>
                <div>
                    <label>
                        Due Date:&nbsp;&nbsp;&nbsp;
                        <input type='date' value={dueDate} onChange={(e) => setDueDate(e.target.value)} />
                    </label>
                </div>
                <div>
                    <button type="submit">Create Task</button>
                    <button onClick={(e) => onClose()}>Cancel</button>
                </div>
            </form>
        </ReactModal>
    );
};

export default AddTaskModal;