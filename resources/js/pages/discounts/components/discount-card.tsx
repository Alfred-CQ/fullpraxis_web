import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { cn } from '@/lib/utils';

type DiscountCardProps = {
    name: string;
    monthly_discount: number;
    enrollment_discount: number;
    className?: string;
};

export function CardDemo({ name, monthly_discount, enrollment_discount, className, ...props }: DiscountCardProps) {
    return (
        <Card className={cn('w-[380px]', className)} {...props}>
            <CardHeader>
                <CardTitle>{name}</CardTitle>
                <CardDescription>Descuentos disponibles</CardDescription>
            </CardHeader>
            <CardContent className="grid gap-4">
                <div className="flex items-center space-x-4 rounded-md border p-4">
                    <div className="flex-1 space-y-1">
                        <p className="text-sm font-medium leading-none">Descuento Mensual</p>
                        <p className="text-sm text-muted-foreground">S./ {monthly_discount}</p>
                    </div>
                </div>
                <div className="flex items-center space-x-4 rounded-md border p-4">
                    <div className="flex-1 space-y-1">
                        <p className="text-sm font-medium leading-none">Descuento de Matrícula</p>
                        <p className="text-sm text-muted-foreground">S./ {enrollment_discount}</p>
                    </div>
                </div>
            </CardContent>
            <CardFooter></CardFooter>
        </Card>
    );
}