import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { EnrollmentForm } from './components/form';

import { useEffect } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matrículas',
        href: '/enrollments',
    },
    {
        title: 'Registro',
        href: '/enrollments/create',
    },
];

interface Props {
    academic_terms: { id: number; name: string }[];
    flash?: {
        success?: string;
        error?: string;
        description?: string;
    };
    errors?: Record<string, string>;
}

export default function Dashboard({ academic_terms, flash}: Props) {
    const { errors } = usePage().props;
    useEffect(() => {
        if (errors) {
            Object.entries(errors).forEach(([field, message]) => {
                toast.error(`Error en ${field}: ${message}`);
            });
        }
        if (flash?.success) {
            toast.success(flash.success, {
                description: flash.description,
            });
        }
        if (flash?.error) {
            toast.error(flash.error, {
                description: flash.description,
            });
        }
        console.log(flash);
    }, [flash, errors]);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Registro" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative flex h-full min-h-[100vh] flex-1 items-center justify-center overflow-hidden rounded-xl border md:min-h-min">
                    <div className="w-full max-w-lg p-6">
                        <EnrollmentForm seasons={academic_terms} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
