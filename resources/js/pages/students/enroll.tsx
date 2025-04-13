import { useEffect } from 'react';

import { Head, usePage } from '@inertiajs/react';

import AppLayout from '@/layouts/app-layout';

import { type BreadcrumbItem } from '@/types';

import { InputForm } from './components/form';

import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Alumnos',
        href: '/students',
    },
    {
        title: 'Registrar',
        href: '/students/create',
    },
];

interface Props {
    flash?: {
        success?: string;
        error?: string;
        description?: string;
    };
    errors?: Record<string, string>;
}

export default function StudentRegister({ flash }: Props) {
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
                <div className="w-full">
                    <InputForm />
                </div>
            </div>
        </AppLayout>
    );
}
