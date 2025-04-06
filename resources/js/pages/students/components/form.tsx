'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';

// import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"

const FormSchema = z.object({
    doi: z.string().min(8, { message: 'El DNI debe tener 8 caracteres.' }).max(8),
    first_name: z.string().min(2, { message: 'El nombre es obligatorio.' }),
    last_name: z.string().min(2, { message: 'El apellido es obligatorio.' }),
    mobile_number: z.string().min(9, { message: 'El teléfono debe tener 9 dígitos.' }).max(9),
    birth_date: z.string().nonempty({ message: 'La fecha de nacimiento es obligatoria.' }),
    guardian_mobile_number: z.string().min(9, { message: 'El teléfono del apoderado debe tener 9 dígitos.' }).max(9),
    graduated_high_school: z.string().min(2, { message: 'El colegio de egreso es obligatorio.' }),
});

export function InputForm() {
    const form = useForm<z.infer<typeof FormSchema>>({
        resolver: zodResolver(FormSchema),
        defaultValues: {
            doi: '',
            first_name: '',
            last_name: '',
            mobile_number: '',
            birth_date: '',
            guardian_mobile_number: '',
            graduated_high_school: '',
        },
    });

    function onSubmit(data: z.infer<typeof FormSchema>) {
        console.log(route('students.enroll.store'));
        router.post(route('students.enroll.store'), data);
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
                <FormField
                    control={form.control}
                    name="doi"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>DNI</FormLabel>
                            <FormControl>
                                <Input placeholder="12345678" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="first_name"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Nombres</FormLabel>
                            <FormControl>
                                <Input placeholder="Juan" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="last_name"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Apellidos</FormLabel>
                            <FormControl>
                                <Input placeholder="Pérez" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="mobile_number"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Teléfono</FormLabel>
                            <FormControl>
                                <Input placeholder="987654321" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="birth_date"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Fecha de Nacimiento</FormLabel>
                            <FormControl>
                                <Input type="date" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="guardian_mobile_number"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Teléfono del Apoderado</FormLabel>
                            <FormControl>
                                <Input placeholder="912345678" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="graduated_high_school"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Colegio de Egreso</FormLabel>
                            <FormControl>
                                <Input placeholder="Colegio Nacional" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <Button type="submit">Crear Alumno</Button>
            </form>
        </Form>
    );
}
