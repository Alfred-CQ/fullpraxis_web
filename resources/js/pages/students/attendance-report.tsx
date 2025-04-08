import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

interface Attendance {
    recorded_at: string;
    attendance_type: string;
}

interface Student {
    student_id: string;
    doi: string;
    first_names: string;
    last_names: string;
    phone_number: string;
}

interface Props {
    student: Student;
    attendances: Record<string, Attendance[]>;
}

export default function AttendanceReport({ student, attendances }: Props) {
    return (
        <AppLayout>
            <Head title="Reporte de Asistencias" />
            <div className="p-6">
                <h1 className="text-2xl font-bold">Reporte de Asistencias</h1>
                <div className="mt-4">
                    <p><strong>DNI:</strong> {student.doi}</p>
                    <p><strong>Nombre:</strong> {student.first_names} {student.last_names}</p>
                    <p><strong>Teléfono:</strong> {student.phone_number}</p>
                </div>
                <div className="mt-6">
                    <h2 className="text-xl font-semibold">Asistencias</h2>
                    {Object.entries(attendances).map(([date, records]) => (
                        <div key={date} className="mt-4">
                            <h3 className="text-lg font-medium">{date}</h3>
                            <ul className="list-disc list-inside">
                                {records.map((record, index) => (
                                    <li key={index}>
                                        {record.recorded_at} - {record.attendance_type}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}