import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { BellRing } from 'lucide-react';

type SeasonCardProps = {
    name: string;
    start_date: string;
    end_date: string;
    monthly_cost: number;
    enrollment_cost: number;
    className?: string;
};

export function CardDemo({ name, start_date, end_date, monthly_cost, enrollment_cost, className, ...props }: SeasonCardProps) {
    return (
        <Card className={cn('w-[380px]', className)} {...props}>
            <CardHeader>
                <CardTitle>{name}</CardTitle>
                <CardDescription></CardDescription>
            </CardHeader>
            <CardContent className="grid gap-4">
                <div>
                    <div className="mb-4 grid grid-cols-[25px_1fr] items-start pb-4 last:mb-0 last:pb-0">
                        <span className="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div className="space-y-1">
                            <p className="text-sm leading-none font-medium">Fecha de Inicio</p>
                            <p className="text-muted-foreground text-sm">{start_date}</p>
                        </div>
                        <span className="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div className="space-y-1">
                            <p className="text-sm leading-none font-medium">Fecha Final</p>
                            <p className="text-muted-foreground text-sm">{end_date}</p>
                        </div>
                    </div>
                </div>
                <div className="flex items-center space-x-4 rounded-md border p-4">
                    <BellRing />
                    <div className="flex-1 space-y-1">
                        <p className="text-sm leading-none font-medium">Costo de Matrícula</p>
                        <p className="text-muted-foreground text-sm">{monthly_cost}</p>
                    </div>
                </div>
                <div className="flex items-center space-x-4 rounded-md border p-4">
                    <BellRing />
                    <div className="flex-1 space-y-1">
                        <p className="text-sm leading-none font-medium">Costo de Mensualidad</p>
                        <p className="text-muted-foreground text-sm">{enrollment_cost}</p>
                    </div>
                </div>
            </CardContent>
            <CardFooter></CardFooter>
        </Card>
    );
}
