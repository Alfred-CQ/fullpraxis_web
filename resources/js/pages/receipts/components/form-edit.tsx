'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const studyAreas = ['Ingenierias', 'Biomedicas', 'Sociales'] as const;

const FormSchema = z.object({
    study_area: z.enum(studyAreas, { errorMap: () => ({ message: 'Seleccione un área de estudio válida.' }) }),
    enrollment_date: z.string().nonempty({ message: 'La fecha de matrícula es obligatoria.' }),
    start_date: z.string().nonempty({ message: 'La fecha de inicio es obligatoria.' }),
    end_date: z.string().nonempty({ message: 'La fecha de fin es obligatoria.' }),
    due_date: z.string().nonempty({ message: 'La fecha de vencimiento es obligatoria.' }),
    total_payment: z.string().nonempty({ message: 'El pago total es obligatorio.' }),
    academic_term_id: z.string().nonempty({ message: 'El ciclo académico es obligatorio.' }),
});

interface Props {
    enrollment: {
        id: number;
        study_area: string;
        enrollment_date: string;
        start_date: string;
        end_date: string;
        due_date: string;
        total_payment: number;
        academic_term_id: number;
    };
    academic_terms: { id: number; name: string }[];
}

export function EnrollmentEditForm({ enrollment, academic_terms }: Props) {
    const form = useForm<z.infer<typeof FormSchema>>({
        resolver: zodResolver(FormSchema),
        defaultValues: {
            study_area: studyAreas.includes(enrollment.study_area as typeof studyAreas[number])
                ? (enrollment.study_area as typeof studyAreas[number])
                : undefined,
            enrollment_date: enrollment.enrollment_date,
            start_date: enrollment.start_date,
            end_date: enrollment.end_date,
            due_date: enrollment.due_date,
            total_payment: enrollment.total_payment.toString(),
            academic_term_id: enrollment.academic_term_id.toString(),
        },
    });

    function onSubmit(data: z.infer<typeof FormSchema>) {
        router.put(`/enrollments/${enrollment.id}`, data);
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
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

                {/* Pago Total */}
                <FormField
                    control={form.control}
                    name="total_payment"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Pago Total</FormLabel>
                            <FormControl>
                                <Input type="number" step="0.01" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />



                {/* Ciclo Académico */}
                <FormField
                    control={form.control}
                    name="academic_term_id"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Ciclo Académico</FormLabel>
                            <FormControl>
                                <Select onValueChange={field.onChange} defaultValue={field.value}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Seleccione un ciclo académico" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {academic_terms.map((term) => (
                                            <SelectItem key={term.id} value={term.id.toString()}>
                                                {term.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <Button type="submit">Guardar Cambios</Button>
            </form>
        </Form>
    );
}