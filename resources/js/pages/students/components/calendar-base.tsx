import { CalendarEvent } from '@/components/types';
import { useState, useEffect } from 'react';
import { EventCalendar } from './custom-calendar';

function mapAttendancesToEvents(attendances: { recorded_at: string; attendance_type: string }[]): CalendarEvent[] {
    return attendances.map((attendance, index) => {
        const isEntry = attendance.attendance_type === 'Entry';
        return {
            id: `${index + 1}`,
            title: isEntry ? 'Registro de Entrada' : 'Registro de Salida',
            start: new Date(attendance.recorded_at),
            end: new Date(attendance.recorded_at),
            color: isEntry ? 'emerald' : 'sky',
        };
    });
}

type CalendarProps = {
    attendances: { recorded_at: string; attendance_type: string }[];
};

export default function Calendar({ attendances }: CalendarProps) {
    const [events, setEvents] = useState<CalendarEvent[]>([]);

    useEffect(() => {
        const mappedEvents = mapAttendancesToEvents(attendances);
        setEvents(mappedEvents);
    }, [attendances]);

    const handleEventUpdate = (updatedEvent: CalendarEvent) => {
        setEvents(events.map((event) => (event.id === updatedEvent.id ? updatedEvent : event)));
    };

    const handleEventDelete = (eventId: string) => {
        setEvents(events.filter((event) => event.id !== eventId));
    };

    return <EventCalendar events={events} onEventUpdate={handleEventUpdate} onEventDelete={handleEventDelete} />;
}