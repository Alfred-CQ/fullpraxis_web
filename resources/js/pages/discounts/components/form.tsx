'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import { router } from '@inertiajs/react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';

import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';

const FormSchema = z.object({
    name: z.string().min(2, { message: 'El nombre es obligatorio.' }),
    monthly_discount: z.coerce.number()
        .min(0, { message: 'El descuento no puede ser negativo' })
        .default(0),
    enrollment_discount: z.coerce.number()
        .min(0, { message: 'El descuento no puede ser negativo' })
        .default(0),
    description: z.string().nullable().optional(),
});


export function DiscountForm() {
    const form = useForm({
        resolver: zodResolver(FormSchema) as any,
        defaultValues: {
            name: '',
            monthly_discount: 0,
            enrollment_discount: 0,
            description: '',
        },
    });

    type FormValues = z.infer<typeof FormSchema>;

    const onSubmit = (data: FormValues) => {
        router.post(route('discounts.store'), data);
    };

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
                {/* Campo: Nombre */}
                <FormField
                    control={form.control}
                    name="name"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Nombre</FormLabel>
                            <FormControl>
                                <Input placeholder="Nombre del descuento" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="monthly_discount"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Descuento Mensual</FormLabel>
                            <FormControl>
                                <Input type="number" placeholder="0.00" {...field} onChange={(e) => field.onChange(Number(e.target.value))} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="enrollment_discount"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Descuento de Matrícula</FormLabel>
                            <FormControl>
                                <Input type="number" placeholder="0.00" {...field} value={field.value ?? ''} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                {/* Campo: Descripción */}
                <FormField
                    control={form.control}
                    name="description"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Descripción</FormLabel>
                            <FormControl>
                                <Input
                                    placeholder="Descripción del descuento (opcional)"
                                    {...field}
                                    value={field.value ?? ''}  // Aseguramos que el valor sea una cadena vacía si es null
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <Button type="submit">Crear Descuento</Button>
            </form>
        </Form>
    );
}
