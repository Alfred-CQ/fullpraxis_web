'use client';

import { router } from '@inertiajs/react';

import { ColumnDef } from '@tanstack/react-table';

import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';

import { CalendarClock, History, IdCard, UserPen } from 'lucide-react';

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
    {
        id: 'select',
        header: ({ table }) => (
            <div className="flex items-center justify-center">
                <Checkbox
                    style={{ borderColor: 'white' }}
                    checked={table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate')}
                    onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
                    aria-label="Select all"
                />
            </div>
        ),
        cell: ({ row }) => (
            <div className="flex items-center justify-center">
                <Checkbox checked={row.getIsSelected()} onCheckedChange={(value) => row.toggleSelected(!!value)} aria-label="Select row" />
            </div>
        ),
        enableSorting: false,
        enableHiding: false,
    },
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
                        <IdCard />
                    </Button>

                    <Button variant="outline" size="icon" onClick={() => router.visit(route('students.calendar', student.student_id))}>
                        <CalendarClock />
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
