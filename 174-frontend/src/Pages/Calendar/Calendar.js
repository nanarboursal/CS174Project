import React, { useState, useEffect } from 'react';
import Cookies from 'js-cookie';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";
import CreateEventModal from './CreateEventModal';
import EditEventModal from './EditEventModal';
import './Calendar.css'

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
            fetch('http://cos-cs106.science.sjsu.edu/~014155765/code/events.php', {
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
                    height='680px'
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

export default Calendar;