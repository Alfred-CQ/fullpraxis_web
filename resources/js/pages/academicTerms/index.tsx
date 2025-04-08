import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { PlusIcon } from 'lucide-react';

import { CardDemo } from './components/season-card';

type academicTerm = {
    id: number;
    name: string;
    start_date: string;
    end_date: string;
    monthly_cost: number;
    enrollment_cost: number;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Ciclos Academicos',
        href: '/academicTerms',
    },
];

export default function academicTermsView({ academicTerms }: { academicTerms: academicTerm[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Ciclos Academicos" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex justify-end">
                    <Button variant="outline" size="sm" onClick={() => router.get(route('academic-terms.create'))}>
                        <PlusIcon />
                        <span className="hidden lg:inline">Agregar</span>
                    </Button>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl  md:min-h-min">
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                    {academicTerms.map((academicTerms) => (
                        <CardDemo
                            key={academicTerms.id}
                            name={academicTerms.name}
                            start_date={academicTerms.start_date}
                            end_date={academicTerms.end_date}
                            monthly_cost={academicTerms.monthly_cost}
                            enrollment_cost={academicTerms.enrollment_cost}
                        />
                    ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
