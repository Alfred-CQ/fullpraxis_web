import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { ReceiptForm } from './components/form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Boletas',
        href: '/receipts',
    },
    {
        title: 'Boletas',
        href: '/receipts/create',
    },
];

interface Props {
    academic_terms: { id: number; name: string }[];
}



interface Props {
    enrollments: { id: number; study_area: string }[];
    discounts: { id: number; name: string }[];
}

export default function CreateReceipt({ enrollments, discounts }: Props) {
    return (
            <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Crear Recibo" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative flex h-full min-h-[100vh] flex-1 items-center justify-center overflow-hidden rounded-xl border md:min-h-min">
                    <div className="w-full max-w-lg p-6">
                        <ReceiptForm enrollments={enrollments} discounts={discounts} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
