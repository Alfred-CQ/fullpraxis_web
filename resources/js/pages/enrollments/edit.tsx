'use client';

import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { z } from 'zod';

import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const studyAreas = ['Ingenierias', 'Biomedicas', 'Sociales']; // Predefined study areas

const FormSchema = z.object({
    Enrollment_code: z.string().min(1, { message: 'El código de matrícula es obligatorio.' }),
    study_area: z.enum(['Ingenierias', 'Biomedicas', 'Sociales'], {
        errorMap: () => ({ message: 'Seleccione un área de estudio válida.' }),
    }),
    Enrollment_date: z.string().nonempty({ message: 'La fecha de matrícula es obligatoria.' }),
    start_date: z.string().nonempty({ message: 'La fecha de inicio es obligatoria.' }),
    end_date: z.string().nonempty({ message: 'La fecha de fin es obligatoria.' }),
    due_date: z.string().nonempty({ message: 'La fecha de vencimiento es obligatoria.' }),
});

interface Enrollment {
    Enrollment_id: string;
    Enrollment_code: string;
    study_area: 'Ingenierias' | 'Biomedicas' | 'Sociales';
    Enrollment_date: string;
    start_date: string;
    end_date: string;
    due_date: string;
}

export default function EditEnrollment({ Enrollment }: { Enrollment: Enrollment }) {
    const form = useForm<z.infer<typeof FormSchema>>({
        resolver: zodResolver(FormSchema),
        defaultValues: {
            Enrollment_code: Enrollment.Enrollment_code,
            study_area: Enrollment.study_area,
            Enrollment_date: Enrollment.Enrollment_date,
            start_date: Enrollment.start_date,
            end_date: Enrollment.end_date,
            due_date: Enrollment.due_date,
        },
    });

    function onSubmit(data: z.infer<typeof FormSchema>) {

        router.put(`/Enrollments/${Enrollment.Enrollment_id}`, data);}

    return (
        <AppLayout>
            <Head title="Editar Matrícula" />
            <Form {...form}>
                <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
                    {/* Código de Matrícula */}
                    <FormField
                        control={form.control}
                        name="Enrollment_code"
                        render={({ field }) => (
                            <FormItem>
                                <FormLabel>Código de Matrícula</FormLabel>
                                <FormControl>
                                    <Input placeholder="Ingrese el código de matrícula" {...field} />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        )}
                    />

                    {/* Área de Estudio */}
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
                                            {studyAreas.map((area) => (
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

                    {/* Fecha de Matrícula */}
                    <FormField
                        control={form.control}
                        name="Enrollment_date"
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

                    {/* Fecha de Inicio */}
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

                    {/* Fecha de Fin */}
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

                    {/* Fecha de Vencimiento */}
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

                    <Button type="submit">Guardar Cambios</Button>
                </form>
            </Form>
        </AppLayout>
    );
}