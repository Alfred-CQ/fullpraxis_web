import AppLayout from '@/layouts/app-layout';
import { Head, usePage } from '@inertiajs/react';
import Calendar from './components/calendar-base';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Alumnos',
        href: '/students',
    },
    {
        title: 'Calendario',
        href: '/students/calendar',
    },
];

type Attendance = {
    recorded_at: string;
    attendance_type: string;
};

type PageProps = {
    props: {
        student: {
            student_id: string;
            first_names: string;
            last_names: string;
        };
        attendances: Attendance[];
    };
};

export default function StudentRegister() {
    const { student, attendances } = (usePage() as unknown as PageProps).props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Registro" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="w-full">
                    <div className="dark bg-muted text-foreground mb-4 rounded-lg px-4 py-3">
                        <p className="flex justify-center text-sm">
                            <a href="#" className="group">
                                <span className="me-1 text-base leading-none">🗓</span>
                                Calendario de asistencias: <strong>{student.first_names} {student.last_names}</strong>
                            </a>
                        </p>
                    </div>
                    <Calendar attendances={attendances} />
                </div>
            </div>
        </AppLayout>
    );
}