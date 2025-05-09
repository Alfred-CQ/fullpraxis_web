import { useEffect, useState } from 'react';

import { Head, router, usePage } from '@inertiajs/react';

import AppLayout from '@/layouts/app-layout';

import { type BreadcrumbItem } from '@/types';

import { Button } from '@/components/ui/button';

import { Student, columns } from './components/columns';
import { DataTable } from './components/data-table';

import { Download, PlusIcon } from 'lucide-react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Alumnos',
        href: '/students',
    },
];

interface Props {
    students: Student[];
    flash?: {
        success?: string;
        error?: string;
        description?: string;
    };
    errors?: Record<string, string>;
}

export default function StudentView({ students, flash }: Props) {
    const { errors } = usePage().props;
    useEffect(() => {
        if (errors) {
            Object.entries(errors).forEach(([field, message]) => {
                toast.error(`Error en ${field}: ${message}`);
            });
        }
        if (flash?.success) {
            toast.success(flash.success, {
                description: flash.description,
            });
        }
        if (flash?.error) {
            toast.error(flash.error, {
                description: flash.description,
            });
        }
    }, [flash, errors]);
    const [selectedStudents, setSelectedStudents] = useState<Student[]>([]);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Alumnos" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex justify-end gap-x-2">
                    <Button variant="outline" size="sm" onClick={() => window.open(route('students.all.carnets'), '_blank')}>
                        <Download />
                        <span className="hidden lg:inline">Descargar Carnets</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        disabled={selectedStudents.length === 0}
                        onClick={() => {
                            const ids = selectedStudents.map((s) => s.student_id);
                            window.open(route('students.selected.carnets', { ids }), '_blank');
                        }}
                    >
                        <Download />
                        <span className="hidden lg:inline">Descargar Carnets Seleccionados</span>
                    </Button>
                    <Button variant="outline" size="sm" onClick={() => router.get(route('students.enroll'))}>
                        <PlusIcon />
                        <span className="hidden lg:inline">Agregar</span>
                    </Button>
                </div>
                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl md:min-h-min">
                    <DataTable columns={columns} data={students} onSelectedChange={setSelectedStudents} />
                </div>
            </div>
        </AppLayout>
    );
}
