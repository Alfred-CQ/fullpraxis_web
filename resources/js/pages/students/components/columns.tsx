'use client';

import { Button } from "@/components/ui/button"
import { ColumnDef } from '@tanstack/react-table';

import { router } from '@inertiajs/react';

import { UserX } from "lucide-react"

export type Student = {
    student_id:string
    doi: string;
    first_name: string;
    last_name: string;
    mobile_number: string;
    //birth_date: string
    guardian_mobile_number: string;
    graduated_high_school: string;
    created_at: string;
};

export const columns: ColumnDef<Student>[] = [
    { accessorKey: '_number', header: '#', cell: ({ row }) => row.index + 1, },

    {
        accessorKey: 'doi',
        header: 'DNI',
    },
    {
        accessorKey: 'first_name',
        header: 'Nombres',
    },
    {
        accessorKey: 'last_name',
        header: 'Apellidos',
    },
    {
        accessorKey: 'mobile_number',
        header: 'Teléfono',
    },
    //   {
    //     accessorKey: "birth_date",
    //     header: "Birth Date",
    //   },
    {
        accessorKey: 'guardian_mobile_number',
        header: 'Teléfono del Apoderado',
    },
    {
        accessorKey: 'graduated_high_school',
        header: 'Colegio de Egreso',
    },
    {
        id: 'actions',
        header: 'Acciones',
        cell: ({ row }) => {
            const student = row.original;

            const handleDelete = () => {
                if (confirm(`¿Estás seguro de que deseas eliminar al estudiante con DNI ${student.doi}?`)) {
                    router.delete(route('students.destroy', student.student_id), {
                        onSuccess: () => alert('Estudiante eliminado correctamente'),
                    });
                }
            };

            return (
                <div className="flex space-x-2">
                    <Button variant="destructive" size="icon"  onClick={handleDelete}>
                    <UserX />
                    </Button>
                </div>
            );
        },
    }
];
