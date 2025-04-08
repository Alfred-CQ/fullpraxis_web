import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { InputForm } from './components/form';

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

export default function StudentRegister() {
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
