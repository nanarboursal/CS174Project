import React, { useState, useEffect } from 'react';
import Cookies from 'js-cookie';
import ReactModal from "react-modal";
import moment from 'moment';
import './edit-task-modal.css';

const EditTaskModal = ({ isOpen, onClose, task }) => {

    const [description, setDescription] = useState("");
    const [dueDate, setDueDate] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault();

        console.log("this is the task: ", task);

        const user = Cookies.get('session');
        console.log("Modal was edited!");

        // Edit task with the selected description and due date
        fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/edit-task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: user, description: task.description, duedate: task.duedate, newdescription: description, newduedate: dueDate })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('successfully updated task');
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
            className="task-edit-modal"
            overlayClassName="modal-overlay"
            isOpen={isOpen}
            onRequestClose={onClose}>
            <h2>Edit Task</h2>
            <form className='edit-task-form' onSubmit={handleSubmit}>
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
                    <button type="submit">Edit Task</button>
                    <button onClick={(e) => onClose()}>Cancel</button>
                </div>
            </form>
        </ReactModal>
    );
};

export default EditTaskModal;