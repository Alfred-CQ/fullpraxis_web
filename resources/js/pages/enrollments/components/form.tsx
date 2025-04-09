'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

// Enum definitions
const AreaDeEstudios = ['Ingenierias', 'Biomedicas', 'Sociales'] as const;
const EstadoDeuda = ['Pagado', 'Pendiente', 'Vencido'] as const;

// Validation schema with Zod
const FormSchema = z.object({
    doi: z.string().min(8, { message: 'El DNI debe tener 8 caracteres.' }).max(8, { message: 'El DNI debe tener 8 caracteres.' }),
    academic_term_id: z.string().nonempty({ message: 'Debe seleccionar un ciclo.' }),
    study_area: z.enum(AreaDeEstudios, { errorMap: () => ({ message: 'Debe seleccionar un área de estudios válida.' }) }),
    enrollment_date: z.string().nonempty({ message: 'La fecha de matrícula es obligatoria.' }),
    start_date: z.string().nonempty({ message: 'La fecha de inicio es obligatoria.' }),
    end_date: z.string().nonempty({ message: 'La fecha de fin es obligatoria.' }),
    due_date: z.string().nonempty({ message: 'La fecha de vencimiento es obligatoria.' }),
    total_payment: z.string().nonempty({ message: 'El pago total es obligatorio.' }),
    debt_status: z.enum(EstadoDeuda, { errorMap: () => ({ message: 'Debe seleccionar un estado de deuda válido.' }) }),
});

export function EnrollmentForm({ seasons }: { seasons: { id: number; name: string }[] }) {
    const form = useForm<z.infer<typeof FormSchema>>({
        resolver: zodResolver(FormSchema),
        defaultValues: {
            doi: '',
            academic_term_id: '',
            study_area: undefined,
            enrollment_date: '',
            start_date: '',
            end_date: '',
            due_date: '',
            total_payment: '',
            debt_status: undefined,
        },
    });

    // Handle form submission
    function onSubmit(data: z.infer<typeof FormSchema>) {
        router.post(route('enrollments.store'), data);
    }
    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
                <FormField
                    control={form.control}
                    name="doi"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>DNI del Estudiante</FormLabel>
                            <FormControl>
                                <Input placeholder="Ingrese el DNI del estudiante" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="academic_term_id"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Ciclo</FormLabel>
                            <FormControl>
                                <Select onValueChange={field.onChange} defaultValue={field.value}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Seleccione un ciclo" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {seasons.map((season) => (
                                            <SelectItem key={season.id} value={season.id.toString()}>
                                                {season.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="study_area"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Área de Estudio</FormLabel>
                            <FormControl>
                                <Select onValueChange={field.onChange} defaultValue={field.value}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Seleccione un área de estudio" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {AreaDeEstudios.map((area) => (
                                            <SelectItem key={area} value={area}>
                                                {area}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="enrollment_date"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Fecha de Matrícula</FormLabel>
                            <FormControl>
                                <Input type="date" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

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

                <FormField
                    control={form.control}
                    name="due_date"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Fecha de Vencimiento</FormLabel>
                            <FormControl>
                                <Input type="date" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="total_payment"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Pago Total</FormLabel>
                            <FormControl>
                                <Input type="number" placeholder="0.00" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="debt_status"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Estado de Deuda</FormLabel>
                            <FormControl>
                                <Select onValueChange={field.onChange} defaultValue={field.value}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Seleccione un estado de deuda" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {EstadoDeuda.map((estado) => (
                                            <SelectItem key={estado} value={estado}>
                                                {estado}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <Button type="submit">Crear Matrícula</Button>
            </form>
        </Form>
    );
}