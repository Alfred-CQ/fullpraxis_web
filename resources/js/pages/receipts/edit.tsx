import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { EnrollmentEditForm } from './components/form-edit';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matrículas',
        href: '/enrollments',
    },
    {
        title: 'Editar',
        href: '/enrollments/edit',
    },
];

interface Props {
    enrollment: {
        id: number;
        study_area: string;
        enrollment_date: string;
        start_date: string;
        end_date: string;
        due_date: string;
        total_payment: number;
        debt_status: string;
        academic_term_id: number;
    };
    academic_terms: { id: number; name: string }[];
}

export default function EditEnrollment({ enrollment, academic_terms }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Editar Matrícula" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative flex h-full min-h-[100vh] flex-1 items-center justify-center overflow-hidden rounded-xl border md:min-h-min">
                    <div className="w-full max-w-lg p-6">
                        <EnrollmentEditForm enrollment={enrollment} academic_terms={academic_terms} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
