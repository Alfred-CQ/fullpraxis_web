'use client';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';

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
};

export const columns: ColumnDef<Enrollment>[] = [
    {
        accessorKey: '_number',
        header: '#',
        cell: ({ row }) => row.index + 1,
    },
    {
        accessorKey: 'student_doi',
        header: 'DNI del Estudiante',
    },
    {
        accessorKey: 'study_area',
        header: 'Área de Estudio',
    },
    {
        accessorKey: 'enrollment_date',
        header: 'Fecha de Matrícula',
        cell: ({ getValue }) => new Date(getValue() as string).toLocaleDateString(),
    },
    {
        accessorKey: 'start_date',
        header: 'Fecha de Inicio',
        cell: ({ getValue }) => new Date(getValue() as string).toLocaleDateString(),
    },
    {
        accessorKey: 'end_date',
        header: 'Fecha de Fin',
        cell: ({ getValue }) => new Date(getValue() as string).toLocaleDateString(),
    },
    {
        accessorKey: 'due_date',
        header: 'Fecha de Vencimiento',
        cell: ({ getValue }) => new Date(getValue() as string).toLocaleDateString(),
    },
    {
        accessorKey: 'total_payment',
        header: 'Pago Total',
        cell: ({ getValue }) => `S/. ${parseFloat(getValue() as string).toFixed(2)}`,
    },
    {
        accessorKey: 'debt_status',
        header: 'Estado de Deuda',
        cell: ({ getValue }) => {
            const status = getValue() as string;

            let colorClass = '';
            switch (status) {
                case 'Paid':
                    colorClass = 'bg-green-400 text-white';
                    break;
                case 'Pending':
                    colorClass = 'bg-yellow-400 text-black';
                    break;
                case 'Overdue':
                    colorClass = 'bg-red-400 text-white';
                    break;
                default:
                    colorClass = 'bg-gray-400 text-white';
            }

            return <Badge className={colorClass}>{status}</Badge>;
        },
    },
    {
        accessorKey: 'academic_term_name',
        header: 'Nombre del Ciclo Académico',
    },
    {
        id: 'actions',
        header: 'Acciones',
        cell: ({ row }) => {
            const enrollment = row.original;
            return (
                <div className="flex space-x-2">
                    <Button variant="outline" size="sm" onClick={() => router.get(route('enrollments.edit', { enrollment: enrollment.id }))}>
                        Editar
                    </Button>
                </div>
            );
        },
    },
];
