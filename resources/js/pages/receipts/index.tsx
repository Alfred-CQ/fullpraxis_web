import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { PlusIcon } from 'lucide-react';
import { columns } from './components/columns';
import { DataTable } from './components/data-table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Boletas',
        href: '/receipts',
    },
];

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


interface Props {
    receipts: Receipt[];
}

export default function ReceiptsView({ receipts }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Recibos" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl bg-[url('/images/main-logo_2x_opacity.png')] bg-[length:850px_auto] bg-center bg-no-repeat p-4">
                <div className="flex justify-end">
                    <Button size="sm" onClick={() => router.get(route('receipts.create'))}>
                        <PlusIcon />
                        <span className="hidden lg:inline">Agregar</span>
                    </Button>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <DataTable columns={columns} data={receipts} />
                </div>
            </div>
        </AppLayout>
    );
}