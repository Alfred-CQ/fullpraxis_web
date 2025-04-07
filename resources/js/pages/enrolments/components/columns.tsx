'use client';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';

export type Enrolment = {
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

export const columns: ColumnDef<Enrolment>[] = [
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
        accessorKey: 'enrolment_code',
        header: 'Código de Matrícula',
    },
    {
        accessorKey: 'study_area',
        header: 'Área de Estudio',
    },
    // {
    //     accessorKey: 'enrolment_date',
    //     header: 'Fecha de Matrícula',
    //     cell: ({ getValue }) => new Date(getValue() as string).toLocaleDateString(),
    // },
    // {
    //     accessorKey: 'start_date',
    //     header: 'Fecha de Inicio',
    //     cell: ({ getValue }) => new Date(getValue() as string).toLocaleDateString(),
    // },
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
                case 'Pagado':
                    colorClass = 'bg-green-400 text-white';
                    break;
                case 'Pendiente':
                    colorClass = 'bg-yellow-400 text-black';
                    break;
                case 'Vencido':
                    colorClass = 'bg-red-400 text-white';
                    break;
                case 'Adelanto':
                    colorClass = 'bg-blue-400 text-white';
                    break;
                default:
                    colorClass = 'bg-gray-400 text-white';
            }

            return <Badge className={colorClass}>{status}</Badge>;
        },
    },
    // {
    //     accessorKey: 'season_name',
    //     header: 'Nombre del Ciclo',
    // },
    {
        id: 'actions',
        header: 'Acciones',
        cell: ({ row }) => {
            const enrolment = row.original;
           // console.log(route('enrolments.edit', enrolment.enrolment_id));
           //console.log("enrolment_id:", enrolment.enrolment_id);
            return (
                <div className="flex space-x-2">
                    <Button
    variant="outline"
    size="sm"
    onClick={() => router.get(route('enrolments.edit', { enrolment: enrolment.enrolment_id }))}>
    Editar
</Button>
                </div>
            );
        },
    },
];
