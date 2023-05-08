<html>
    <head>
        <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
        <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
        <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/core/main.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/daygrid/main.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/timegrid/main.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/list/main.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/interaction/main.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://unpkg.com/react-modal/umd/react-modal.min.js"></script>
        <link rel="stylesheet" href="Calendar.css" />
        <link rel="stylesheet" href="CreateEventModal.css" />
    </head>
    <div id="root"></div>
    <script type="text/babel">
        const { useState } = React;
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
                            window.location.href = "/calendar";
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
                            window.location.href = "/calendar";
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
                className="create-modal" 
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

            return (
                <div className='parent'>
                    <div className='calendar'>
                        <FullCalendar
                            plugins={[ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ]}
                            initialView={view}
                            dateClick={handleDateClick}
                            selectable={true}
                            eventClick={handleEventClick}
                            headerToolbar={{
                                start: "prev,next today",
                                center: "title",
                                end: "dayGridMonth,timeGridWeek,listMonth",
                            }}
                            eventBackgroundColor='#2c3e50'
                            eventBorderColor='#2c3e50'
                            events={events}
                        />
                    </div>
                    
                    <CreateEventModal isOpen={showModal} onClose={() => setShowModal(false)} date={selectedDate} />
                    <EditEventModal isOpen={showEditModal} onClose={() => setShowEditModal(false)} oldtitle={selectedEvent} />
                </div>
            );
        }
        ReactDOM.render(<Calendar />, document.getElementById("root"));
    </script>
</html>