import MyCalendar from "./components/calendar";

export default function CalendarPage({ student }: { student: { student_id: string; first_names: string; last_names: string } }) {
  return (
    <div>
      <h1 className="text-xl font-bold">
        Calendario de {student.first_names} {student.last_names}
      </h1>
      <MyCalendar />
    </div>
  );
}