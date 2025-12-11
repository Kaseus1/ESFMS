import './bootstrap';
import '../css/app.css';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

// ✅ Add Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        // Use Blade JSON directly
        const calendarEvents = window.calendarEvents || []; // Blade passes @json($calendarEvents)

        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            events: calendarEvents,
            eventColor: '#3b82f6',
            eventClick: function(info) {
                Swal.fire({
                    title: info.event.title,
                    html: `<p>Status: <strong>${info.event.extendedProps.status}</strong></p>
                           <p>Start: ${info.event.start.toLocaleString()}</p>
                           <p>End: ${info.event.end ? info.event.end.toLocaleString() : ''}</p>`,
                    icon: 'info'
                });
            }
        });

        calendar.render();
    }
});