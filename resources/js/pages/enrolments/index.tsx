import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { PlusIcon } from 'lucide-react';
import { columns } from './components/columns';
import { DataTable } from './components/data-table';

type Enrolment = {
    enrolment_id: number;
    enrolment_code: string;
    study_area: string;
    enrolment_date: string;
    start_date: string;
    end_date: string;
    due_date: string;
    total_payment: number;
    debt_status: boolean;
    student_doi: string;
    season_name: string;
};

interface Props {
    enrolments: Enrolment[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Enrollments',
        href: '/enrolments',
    },
];


export default function EnrolmentsView({ enrolments }: Props) {

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Students" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className=" flex justify-end">
                    <Button variant="outline" size="sm"  onClick={() => router.get(route('enrolments.create'))}>
                        <PlusIcon />
                        <span className="hidden lg:inline">Agregar</span>
                    </Button>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <DataTable columns={columns} data={enrolments} />
                </div>
            </div>
        </AppLayout>
    );
}
