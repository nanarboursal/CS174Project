<html>
    <head>
        <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
        <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
        <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.0/js.cookie.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/index.global.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.6/index.global.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.6/index.global.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.6/index.global.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.6/index.global.min.js'></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react-modal/3.14.3/react-modal.min.js"
            integrity="sha512-MY2jfK3DBnVzdS2V8MXo5lRtr0mNRroUI9hoLVv2/yL3vrJTam3VzASuKQ96fLEpyYIT4a8o7YgtUs5lPjiLVQ=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="Calendar.css" />
        <link rel="stylesheet" href="CreateEventModal.css" />
        <link rel="stylesheet" href="EditEventModal.css" />
        <link rel="stylesheet" href="Navigation.css">
    </head>
    <div id="root"></div>
    <script type="text/babel">
        const { useState, useEffect, useRef } = React;
        const Cookies = window.Cookies;


        // *****************************

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
                        window.location.href = "http://cos-cs106.science.sjsu.edu/~013879866/code/CalendarComponent.php";
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
                <form onSubmit={handleSubmit}>
                    <label>
                    Title:
                    <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} />
                    </label>
                    <button type="submit">Create Event</button>
                    <button onClick={(e) => onClose()}>Cancel</button>
                </form>
                </ReactModal>
            );
            };

        // *****************************

        const EditEventModal = ({ isOpen, onClose, oldtitle }) => {
            const [title, setTitle] = useState("");
            const [date, setDate] = useState("");
            const user = Cookies.get('session');

            function handleDateUpdate(e) {
                e.preventDefault();

                fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/edit-event-date.php', {
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
                            window.location.href = "http://cos-cs106.science.sjsu.edu/~013879866/code/CalendarComponent.php";
                        } else {
                            console.log(data.message);
                        }
                    })
                    .catch(error => console.error(error));
            }

            function handleTitleUpdate(e) {
                e.preventDefault();

                fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/edit-event-title.php', {
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
                            window.location.href = "http://cos-cs106.science.sjsu.edu/~013879866/code/CalendarComponent.php";
                        } else {
                            console.log(data.message);
                        }
                    })
                    .catch(error => console.error(error));
            }

            function handleDelete(e) {
                e.preventDefault();

                fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/edit-event-delete.php', {
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
                            window.location.href = "http://cos-cs106.science.sjsu.edu/~013879866/code/CalendarComponent.php";
                        } else {
                            console.log(data.message);
                        }
                    })
                    .catch(error => console.error(error));
            }

            function handleExport(e) {
                e.preventDefault();

                fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/edit-event-export.php', {
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
                            window.location.href = "http://cos-cs106.science.sjsu.edu/~013879866/code/CalendarComponent.php";
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
            <form >
                <label>
                Title:
                <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} />
                </label>
                <button onClick={handleTitleUpdate}>Update title</button>
                <label>
                Date (yyyy-mm-dd):
                <input type="text" value={date} onChange={(e) => setDate(e.target.value)} />
                </label>
                <button onClick={handleDateUpdate}>Update date</button>
                <button onClick={handleDelete}>Delete event</button>
                <button onClick={handleExport}>Export to todo list</button>
                <button onClick={(e) => onClose()}>Cancel</button>
            </form>
            </ReactModal>
        );
        };

        // *****************************

        function Calendar() {
            const user = Cookies.get('session');

            const [events, setEvents] = useState([]);

            const [view, setView] = useState("dayGridMonth");

            // calendar view
            const handleViewChange = (newView) => {
                setView(newView);
            };

            const viewButtons = {
                month: {
                    text: "Month",
                    click: () => handleViewChange("dayGridMonth"),
                },
                week: {
                    text: "Week",
                    click: () => handleViewChange("timeGridWeek"),
                },
                list: {
                    text: "List",
                    click: () => handleViewChange("listMonth"),
                },
            };


            // call server to get all events

            useEffect(() => {
                if (user) {
                    fetch('http://cos-cs106.science.sjsu.edu/~013879866/code/events.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ username: user })
                    })
                        .then(response => response.json())
                        .then(data => setEvents(data))
                        .catch(error => console.error(error));
                };
            }, []);

            // open modal when date is clicked

            const [selectedDate, setSelectedDate] = useState(null);
            const [showModal, setShowModal] = useState(false);
            
            const handleDateClick = (clickInfo) => {
                console.log(clickInfo.date);
                setSelectedDate(clickInfo.date);
                setShowModal(true);
            };

            // open edit modal when event is clicked

            const [selectedEvent, setSelectedEvent] = useState(null);
            const [showEditModal, setShowEditModal] = useState(false);

            const handleEventClick = (clickInfo) => {
                setSelectedEvent(clickInfo.event._def.title);
                setShowEditModal(true);
            };

            const calendarRef = useRef(null);

            useEffect(() => {
                const calendar = new window.FullCalendar.Calendar(calendarRef.current, {
                                plugins:[ 'dayGrid', 'timeGrid', 'list', 'interaction' ],
                                initialView: view,
                                dateClick: handleDateClick,
                                selectable: true,
                                eventClick: handleEventClick,
                                headerToolbar:{
                                    start: "prev,next today",
                                    center: "title",
                                    end: "dayGridMonth,timeGridWeek,listMonth",
                                    views: viewButtons
                                },
                                eventBackgroundColor:'#2c3e50',
                                eventBorderColor:'#2c3e50',
                                events: events
                            });
                calendar.render();
            }, [events, view, viewButtons]);

            return (
                <div className='parent'>
                    
                    <div ref={calendarRef} className='calendar'>
                        
                    </div>
                    <CreateEventModal isOpen={showModal} onClose={() => setShowModal(false)} date={selectedDate} />
                    <EditEventModal isOpen={showEditModal} onClose={() => setShowEditModal(false)} oldtitle={selectedEvent} />
                </div>
            );
        }
        // ReactDOM.render(<Calendar />, document.getElementById("root"));
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
                            <a href="http://cos-cs106.science.sjsu.edu/~013879866/code/CalendarComponent.php" class='button'>Calendar</a>
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
        ReactDOM.render(<><Navigation /><Calendar /></>, document.getElementById("root"));
    </script>
</html>