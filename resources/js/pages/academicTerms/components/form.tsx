'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';

// Esquema de validación con Zod
const FormSchema = z.object({
    name: z.string().min(2, { message: 'El nombre es obligatorio.' }),
    start_date: z.string().nonempty({ message: 'La fecha de inicio es obligatoria.' }),
    end_date: z.string().nonempty({ message: 'La fecha de fin es obligatoria.' }),
    monthly_cost: z
        .string()
        .nonempty({ message: 'El costo mensual es obligatorio.' })
      ,
    enrollment_cost: z
        .string()
        .nonempty({ message: 'El costo de matrícula es obligatorio.' })
});

export function SeasonForm() {
    const form = useForm<z.infer<typeof FormSchema>>({
        resolver: zodResolver(FormSchema),
        defaultValues: {
            name: '',
            start_date: '',
            end_date: '',
            monthly_cost: '0',
            enrollment_cost: '0',
        },
    });

    // Función para manejar el envío del formulario
    function onSubmit(data: z.infer<typeof FormSchema>) {
        router.post(route('academic-terms.store'), data);
    }

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
                                <Input placeholder="Nombre de la temporada" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                {/* Campo: Fecha de Inicio */}
                <FormField
                    control={form.control}
                    name="start_date"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Fecha de Inicio</FormLabel>
                            <FormControl>
                                <Input type="date" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                {/* Campo: Fecha de Fin */}
                <FormField
                    control={form.control}
                    name="end_date"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Fecha de Fin</FormLabel>
                            <FormControl>
                                <Input type="date" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                {/* Campo: Costo Mensual */}
                <FormField
                    control={form.control}
                    name="monthly_cost"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Costo Mensual</FormLabel>
                            <FormControl>
                                <Input type="number" placeholder="0.00" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                {/* Campo: Costo de Matrícula */}
                <FormField
                    control={form.control}
                    name="enrollment_cost"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Costo de Matrícula</FormLabel>
                            <FormControl>
                                <Input type="number" placeholder="0.00" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                {/* Botón de envío */}
                <Button type="submit">Crear Temporada</Button>
            </form>
        </Form>
    );
}
