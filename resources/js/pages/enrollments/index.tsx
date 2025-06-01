import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { PlusIcon } from 'lucide-react';
import { columns } from './components/columns';
import { DataTable } from './components/data-table';

export type Enrollment = {
    id: number;
    study_area: string;
    enrollment_date: string;
    start_date: string;
    end_date: string;
    due_date: string;
    total_payment: number;
    debt_status: string;
    student_doi: string;
    academic_term_name: string;
    shift: string;
};

interface Props {
    enrollments: Enrollment[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matrículas',
        href: '/enrollments',
    },
];

export default function EnrollmentsView({ enrollments }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Matrículas" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl bg-[url('/images/main-logo_2x_opacity.png')] bg-[length:850px_auto] bg-center bg-no-repeat p-4">
                <div className="flex justify-end">
                    <Button size="sm" onClick={() => router.get(route('enrollments.create'))}>
                        <PlusIcon />
                        <span className="hidden lg:inline">Agregar</span>
                    </Button>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <DataTable columns={columns} data={enrollments} />
                </div>
            </div>
        </AppLayout>
    );
}
