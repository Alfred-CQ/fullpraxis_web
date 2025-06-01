import { useEffect, useState } from 'react';

import { Head, router, usePage } from '@inertiajs/react';

import AppLayout from '@/layouts/app-layout';

import { type BreadcrumbItem } from '@/types';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input'; // <-- IMPORTANTE

import { Student, columns } from './components/columns';
import { DataTable } from './components/data-table';

import { Download, PlusIcon, Upload } from 'lucide-react';
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
    const [dniFilter, setDniFilter] = useState('');
    const filteredStudents = students.filter((student) => student.doi.toLowerCase().includes(dniFilter.toLowerCase()));

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
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl bg-[url('/images/main-logo_2x_opacity.png')] bg-[length:850px_auto] bg-center bg-no-repeat p-4">
                <div className="flex justify-between gap-x-2">
                    <Input placeholder="Filtrar por DNI..." value={dniFilter} onChange={(e) => setDniFilter(e.target.value)} className="max-w-xs" />
                    <div className="flex gap-x-2">
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={() => {
                                const input = document.createElement('input');
                                input.type = 'file';
                                input.accept = '.csv, .xlsx';
                                input.onchange = (e) => {
                                    const file = (e.target as HTMLInputElement).files?.[0];
                                    if (file) {
                                        const formData = new FormData();
                                        formData.append('file', file);
                                        router.post(route('students.import'), formData, {
                                            onFinish: () => {
                                                input.value = ''; // Limpiar el input después de la carga
                                            },
                                        });
                                    }
                                };
                                input.click();
                            }}
                        >
                            <Upload />
                            <span className="hidden lg:inline">Importar Datos</span>
                        </Button>
                        <Button variant="outline" size="sm" onClick={() => window.open(route('students.export'), '_blank')}>
                            <Download />
                            <span className="hidden lg:inline">Exportar Datos</span>
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
                        <Button size="sm" onClick={() => router.get(route('students.enroll'))}>
                            <PlusIcon />
                            <span className="hidden lg:inline">Agregar</span>
                        </Button>
                    </div>
                </div>
                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl md:min-h-min">
                    <DataTable columns={columns} data={filteredStudents} onSelectedChange={setSelectedStudents} />
                </div>
            </div>
        </AppLayout>
    );
}
