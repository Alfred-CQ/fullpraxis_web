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
const EstadoDeuda = ['Pagado', 'Adelanto', 'Pendiente', 'Vencido'] as const;

// Validation schema with Zod
const FormSchema = z.object({
    doi: z.string().min(8, { message: 'El DNI debe tener 8 caracteres.' }).max(8, { message: 'El DNI debe tener 8 caracteres.' }),
    enrolment_code: z.string().nonempty({ message: 'El código de matrícula es obligatorio.' }),
    season_id: z.string().nonempty({ message: 'Debe seleccionar un ciclo.' }),
    study_area: z.enum(AreaDeEstudios, { errorMap: () => ({ message: 'Debe seleccionar un área de estudios válida.' }) }),
    enrolment_date: z.string().nonempty({ message: 'La fecha de matrícula es obligatoria.' }),
    start_date: z.string().nonempty({ message: 'La fecha de inicio es obligatoria.' }),
    end_date: z.string().nonempty({ message: 'La fecha de fin es obligatoria.' }),
    due_date: z.string().nonempty({ message: 'La fecha de vencimiento es obligatoria.' }),
    total_payment: z.string().nonempty({ message: 'El pago total es obligatorio.' }),
    debt_status: z.enum(EstadoDeuda, { errorMap: () => ({ message: 'Debe seleccionar un estado de deuda válido.' }) }),
});

export function EnrolmentForm({ seasons }: { seasons: { season_id: number; name: string }[] }) {
    const form = useForm<z.infer<typeof FormSchema>>({
        resolver: zodResolver(FormSchema),
        defaultValues: {
            doi: '',
            season_id: '',
            enrolment_code: '',
            study_area: undefined,
            enrolment_date: '',
            start_date: '',
            end_date: '',
            due_date: '',
            total_payment: '',
            debt_status: undefined,
        },
    });

    // Handle form submission
    function onSubmit(data: z.infer<typeof FormSchema>) {
        router.post(route('enrolments.store'), data);
    }
    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
                {/* DNI Field */}
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
                    name="season_id"
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
                                            <SelectItem key={season.season_id} value={season.season_id.toString()}>
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
                    name="enrolment_code"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Código de Matrícula</FormLabel>
                            <FormControl>
                                <Input placeholder="Ingrese el código" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                {/* Study Area Dropdown */}
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

                {/* Enrolment Date */}
                <FormField
                    control={form.control}
                    name="enrolment_date"
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

                {/* Start Date */}
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

                {/* End Date */}
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

                {/* Due Date */}
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

                {/* Total Payment */}
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

                {/* Debt Status Dropdown */}
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

                {/* Submit Button */}
                <Button type="submit">Crear Matrícula</Button>
            </form>
        </Form>
    );
}