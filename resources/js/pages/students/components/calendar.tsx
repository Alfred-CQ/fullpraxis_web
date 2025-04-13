

import { Calendar, dateFnsLocalizer } from 'react-big-calendar';

import { format } from 'date-fns';
import {parse} from 'date-fns/parse';
import {startOfWeek} from 'date-fns/startOfWeek';
import {getDay} from 'date-fns/getDay';
import {es} from 'date-fns/locale/es';
import 'react-big-calendar/lib/css/react-big-calendar.css';

const locales = {
  'es': es,
};

const localizer = dateFnsLocalizer({
  format,
  parse,
  startOfWeek,
  getDay,
  locales,
});

const today = new Date();
const year = today.getFullYear();
const month = today.getMonth();
const day = today.getDate();

const events = [
    {
      title: 'Reunión con el equipo',
      start: new Date(year, month, day, 9, 0),   // 9:00 AM
      end: new Date(year, month, day, 10, 0),    // 10:00 AM
    },
    {
      title: 'Llamada con cliente',
      start: new Date(year, month, day, 11, 30), // 11:30 AM
      end: new Date(year, month, day, 12, 30),   // 12:30 PM
    },
    {
      title: 'Almuerzo',
      start: new Date(year, month, day, 13, 0),  // 1:00 PM
      end: new Date(year, month, day, 14, 0),    // 2:00 PM
    },
    {
      title: 'Presentación del proyecto',
      start: new Date(year, month, day, 15, 30), // 3:30 PM
      end: new Date(year, month, day, 17, 0),    // 5:00 PM
    },
  ];

export default function MyCalendar() {
  return (
    <div style={{ height: 700 }}>
      <Calendar
      culture='es'
        localizer={localizer}
        events={events}
        startAccessor="start"
        endAccessor="end"
        style={{ height: 700 }}
      />
    </div>
  );
}