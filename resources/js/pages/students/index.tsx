import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Download, PlusIcon } from 'lucide-react';
import { Student, columns } from './components/columns';
import { DataTable } from './components/data-table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Alumnos',
        href: '/students',
    },
];

interface Props {
    students: Student[];
}

export default function StudentView({ students }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Alumnos" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex justify-end">
                    <Button variant="outline" size="sm" onClick={() => window.open(route('students.carnets'), '_blank')}>
                        <Download />
                        <span className="hidden lg:inline">Descargar Carnets</span>
                    </Button>
                    <Button variant="outline" size="sm" onClick={() => router.get(route('students.enroll'))}>
                        <PlusIcon />
                        <span className="hidden lg:inline">Agregar</span>
                    </Button>
                </div>
                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl md:min-h-min">
                    <DataTable columns={columns} data={students} />
                </div>
            </div>
        </AppLayout>
    );
}
