import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { EditForm } from './components/form-edit';


type StudentData = {
    student_id: string;
    doi: string;
    first_name: string;
    last_name: string;
    mobile_number: string;
    birth_date: string;
    guardian_mobile_number: string;
    graduated_high_school: string;
    photo_url?: string;
};

type PageProps = {
    props: {
        student: StudentData;
    };
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Alumnos',
        href: '/students',
    },
    {
        title: 'Editar',
        href: '/students/edit',
    },
];

export default function StudentEdit() {

    const { student } = (usePage() as unknown as PageProps).props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Editar Alumno" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="w-full">

                    <EditForm initialData={student} />
                </div>
            </div>
        </AppLayout>
    );
}