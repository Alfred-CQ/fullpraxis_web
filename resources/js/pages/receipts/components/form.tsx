'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';

// Validation schema with Zod
const FormSchema = z.object({
    doi: z.string().min(8, { message: 'El doi debe tener 8 caracteres.' }).max(8, { message: 'El doi debe tener 8 caracteres.' }),
    discount_id: z.string().optional(),
    receipt_code: z.string().max(10, { message: 'Máximo 10 caracteres.' }).nonempty({ message: 'El código es obligatorio.' }),
    payment_date: z.string().nonempty({ message: 'La fecha de pago es obligatoria.' }),
    enrollment_payment:z
    .string()
    .nonempty({ message: 'El costo mensual es obligatorio.' })
  ,
    monthly_payment: z
    .string()
    .nonempty({ message: 'El costo de matrícula es obligatorio.' }),
    notes: z.string().optional(),
});

interface Props {
    enrollments: { id: number; study_area: string }[];
    discounts: { id: number; name: string }[];
}

export function ReceiptForm({ enrollments, discounts }: Props) {
    const form = useForm<z.infer<typeof FormSchema>>({
        resolver: zodResolver(FormSchema),
        defaultValues: {
            doi: '',
            discount_id: '',
            receipt_code: '',
            payment_date: '',
            enrollment_payment: '',
            monthly_payment: '',
            notes: '',
        },
    });

    // Handle form submission
    function onSubmit(data: z.infer<typeof FormSchema>) {
        router.post(route('receipts.store'), data);
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
                    name="discount_id"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Descuento</FormLabel>
                            <FormControl>
                                <Select onValueChange={field.onChange} defaultValue={field.value}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Seleccione un descuento (opcional)" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {discounts.map((discount) => (
                                            <SelectItem key={discount.id} value={discount.id.toString()}>
                                                {discount.name}
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
                    name="receipt_code"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Código de Recibo</FormLabel>
                            <FormControl>
                                <Input placeholder="Ingrese el código" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="payment_date"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Fecha de Pago</FormLabel>
                            <FormControl>
                                <Input type="date" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="enrollment_payment"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Pago de Matrícula</FormLabel>
                            <FormControl>
                                <Input type="number" step="0.01" placeholder="0.00" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="monthly_payment"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Pago Mensual</FormLabel>
                            <FormControl>
                                <Input type="number" step="0.01" placeholder="0.00" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="notes"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Notas</FormLabel>
                            <FormControl>
                                <Textarea placeholder="Ingrese notas (opcional)" {...field} />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />

                <Button type="submit">Guardar</Button>
            </form>
        </Form>
    );
}