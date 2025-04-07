import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { EnrolmentForm } from './components/form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Enrolment',
        href: '/enrolments/create',
    },
];

interface Props {
    seasons: { season_id: number; name: string }[];
}



export default function Dashboard({ seasons }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Registro" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex h-full items-center justify-center border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <div className="w-full max-w-lg p-6">
                        <EnrolmentForm seasons={seasons} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
