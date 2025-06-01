import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { PlusIcon } from 'lucide-react';

import { CardDemo } from './components/discount-card';

type Discount = {
    discount_id: number;
    name: string;
    monthly_discount: number;
    enrollment_discount: number;
    description: string | null;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Descuentos',
        href: '/discounts',
    },
];

export default function DiscountsView({ discounts }: { discounts: Discount[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Descuentos" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl bg-[url('/images/main-logo_2x_opacity.png')] bg-[length:550px_auto] bg-center bg-no-repeat p-4">
                <div className="flex justify-end">
                    <Button size="sm" onClick={() => router.get(route('discounts.create'))}>
                        <PlusIcon />
                        <span className="hidden lg:inline">Agregar</span>
                    </Button>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl md:min-h-min">
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        {discounts.map((discount) => (
                            <CardDemo
                                key={discount.discount_id}
                                name={discount.name}
                                monthly_discount={discount.monthly_discount}
                                enrollment_discount={discount.enrollment_discount}
                                description={discount.description || 'Descuentos disponibles'}
                            />
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}