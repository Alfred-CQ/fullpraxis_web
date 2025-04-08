'use client';

import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';

export type Receipt = {
    id: number;
    amount: number;
    date: string;
    description: string;
    receipt_code: string;
    payment_date: string;
    enrollment_payment: number;
    monthly_payment: number; // Added property
    notes: string; // Added property
};
export const columns: ColumnDef<Receipt>[] = [

    {
        accessorKey: 'receipt_code',
        header: 'Código de Recibo',
    },
    {
        accessorKey: 'payment_date',
        header: 'Fecha de Pago',
        cell: ({ getValue }) => new Date(getValue() as string).toLocaleDateString(),
    },
    {
        accessorKey: 'enrollment_payment',
        header: 'Pago de Matrícula',
        cell: ({ getValue }) => `S/. ${parseFloat(getValue() as string).toFixed(2)}`,
    },
    {
        accessorKey: 'monthly_payment',
        header: 'Pago Mensual',
        cell: ({ getValue }) => `S/. ${parseFloat(getValue() as string).toFixed(2)}`,
    },
    {
        accessorKey: 'notes',
        header: 'Notas',
    },
    {
        id: 'actions',
        header: 'Acciones',
        cell: ({ row }) => {
            const receipt = row.original;
            return (
                <div className="flex space-x-2">
                    {/* <Button variant="outline" size="sm" onClick={() => router.get(route('receipts.edit', { receipt: receipt.id }))}>
                        Editar
                    </Button> */}
                </div>
            );
        },
    },
];