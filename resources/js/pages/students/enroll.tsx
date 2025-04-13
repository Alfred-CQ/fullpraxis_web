import { useEffect } from 'react';

import { Head } from '@inertiajs/react';

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
}

export default function StudentRegister({ flash }: Props) {
    useEffect(() => {
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
    }, [flash]);
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
