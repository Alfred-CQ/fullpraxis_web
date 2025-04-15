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
    shift: string;
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
        cell: ({ getValue }) => {
            const rawDate = getValue() as string;
            const date = new Date(rawDate + 'T00:00:00');
            return date.toLocaleDateString('es-PE', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
            });
          },
    },
    {
        accessorKey: 'start_date',
        header: 'Fecha de Inicio',
        cell: ({ getValue }) => {
          const rawDate = getValue() as string;
          const date = new Date(rawDate + 'T00:00:00');
          return date.toLocaleDateString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
          });
        },
      },
    {
        accessorKey: 'end_date',
        header: 'Fecha de Fin',
        cell: ({ getValue }) => {
            const rawDate = getValue() as string;
            const date = new Date(rawDate + 'T00:00:00');
            return date.toLocaleDateString('es-PE', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
            });
          },
    },
    {
        accessorKey: 'due_date',
        header: 'Fecha de Vencimiento',
        cell: ({ getValue }) => {
            const rawDate = getValue() as string;
            const date = new Date(rawDate + 'T00:00:00');
            return date.toLocaleDateString('es-PE', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
            });
          },
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

            const debtStatusTranslations = {
                'Paid': 'Pagado',
                'Pending': 'Pendiente',
                'Overdue': 'Vencido'
            };

            const translatedStatus = debtStatusTranslations[status as keyof typeof debtStatusTranslations] || status;

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

            return <Badge className={colorClass}>{translatedStatus}</Badge>;
        },
    },
    {
        accessorKey: 'academic_term_name',
        header: 'Nombre del Ciclo Académico',
    },
    {
        accessorKey: "shift",
        header: "Turno",
        cell: ({ row }) => {
          const shift = row.getValue("shift") as string;
          const formatted = {
            morning: 'Mañana',
            afternoon: 'Tarde',
            both: 'Ambos'
          }[shift] || shift;
          
          return <div className="font-medium">{formatted}</div>
        }
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
