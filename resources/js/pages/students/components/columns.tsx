'use client';

import { Button } from '@/components/ui/button';
import { ColumnDef } from '@tanstack/react-table';

import { router } from '@inertiajs/react';

import { FileText, History, UserPen } from 'lucide-react';

export type Student = {
    student_id: string;
    doi: string;
    first_names: string;
    last_names: string;
    phone_number: string;
    //birth_date: string
    guardian_phone: string;
    high_school_name: string;
    created_at: string;
};

export const columns: ColumnDef<Student>[] = [
    { accessorKey: '_number', header: '#', cell: ({ row }) => row.index + 1 },

    {
        accessorKey: 'doi',
        header: 'DNI',
    },
    {
        accessorKey: 'first_names',
        header: 'Nombres',
    },
    {
        accessorKey: 'last_names',
        header: 'Apellidos',
    },
    {
        accessorKey: 'phone_number',
        header: 'Teléfono',
    },
    //   {
    //     accessorKey: "birth_date",
    //     header: "Birth Date",
    //   },
    {
        accessorKey: 'guardian_phone',
        header: 'Apoderado',
    },
    // {
    //     accessorKey: 'high_school_name',
    //     header: 'Colegio de Egreso',
    // },
    {
        id: 'actions',
        header: 'Acciones',
        cell: ({ row }) => {
            const student = row.original;

            // const handleDelete = () => {
            //     if (confirm(`¿Estás seguro de que deseas eliminar al estudiante con DNI ${student.doi}?`)) {
            //         router.delete(route('students.destroy', student.student_id), {
            //             onSuccess: () => alert('Estudiante eliminado correctamente'),
            //         });
            //     }
            // };

            const handleEdit = () => {
                router.visit(route('students.edit', student.student_id));
            };

            const handleGenerateCarnet = () => {
                window.open(route('students.carnet', student.student_id), '_blank');
            };

            return (
                <div className="flex space-x-2">
                    {/* <Button variant="destructive" size="icon"  onClick={handleDelete}>
                    <UserX />
                    </Button> */}
                    <Button variant="outline" size="icon" onClick={handleEdit}>
                        <UserPen />
                    </Button>

                    <Button variant="outline" size="icon" onClick={handleGenerateCarnet}>
                        <FileText />
                    </Button>

                    <Button
                        variant="outline"
                        size="icon"
                        onClick={() => window.open(route('students.attendance-report-pdf', student.student_id), '_blank')}
                    >
                        <History />
                    </Button>
                </div>
            );
        },
    },
];
