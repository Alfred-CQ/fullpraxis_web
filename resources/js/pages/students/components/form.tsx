'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
import { useState } from 'react';
import { Camera, Loader2 } from 'lucide-react';

const FormSchema = z.object({
    doi: z.string().min(8, { message: 'El DNI debe tener 8 caracteres.' }).max(8),
    first_name: z.string().min(2, { message: 'El nombre es obligatorio.' }),
    last_name: z.string().min(2, { message: 'El apellido es obligatorio.' }),
    phone_number: z.string().min(9, { message: 'El teléfono debe tener 9 dígitos.' }).max(9),
    birth_date: z.string().nonempty({ message: 'La fecha de nacimiento es obligatoria.' }),
    guardian_phone: z.string().min(9, { message: 'El teléfono del apoderado debe tener 9 dígitos.' }).max(9),
    high_school_name: z.string().min(2, { message: 'El colegio de egreso es obligatorio.' }),
});


export function InputForm() {
    const [selectedFile, setSelectedFile] = useState<File | null>(null);
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const form = useForm<z.infer<typeof FormSchema>>({
        resolver: zodResolver(FormSchema),
        defaultValues: {
            doi: '',
            first_name: '',
            last_name: '',
            phone_number: '',
            birth_date: '',
            guardian_phone: '',
            high_school_name: '',
        },
        
    });

    async function onSubmit(data: z.infer<typeof FormSchema>) {
        setIsSubmitting(true);

        const formData = new FormData();
        formData.append('doi', data.doi);
        formData.append('first_name', data.first_name);
        formData.append('last_name', data.last_name);
        formData.append('phone_number', data.phone_number);
        formData.append('birth_date', data.birth_date);
        formData.append('guardian_phone', data.guardian_phone);
        formData.append('high_school_name', data.high_school_name);
        

        if (selectedFile) {
            formData.append('photo', selectedFile);
        }

        try {
            await router.post(route('students.enroll.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
        } finally {
            setIsSubmitting(false);
        }
    }

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setSelectedFile(file);
            setPreviewUrl(URL.createObjectURL(file));
        }
    };

    return (
        <div className="flex flex-col gap-6">
            <Card className="overflow-hidden">
                <CardContent className="grid p-0 md:grid-cols-2">
                    <Form {...form}>
                        <div className="relative hidden md:block">
                            <div className="flex h-full flex-col items-center justify-center">
                                <label htmlFor="file-upload" className="mx-auto block">
                                    <Card
                                        className={`flex h-[400px] w-[325px] items-center justify-center rounded-md border border-dashed text-sm`}
                                        style={{
                                            backgroundImage: previewUrl ? `url(${previewUrl})` : 'none',
                                            backgroundSize: 'cover',
                                            backgroundPosition: 'center',
                                        }}
                                    >
                                        {!previewUrl && (
                                            <p className="text-muted-foreground text-sm">
                                                {selectedFile ? 'Cambiar archivo' : 'Seleccionar una foto'}
                                            </p>
                                        )}
                                    </Card>
                                    <input id="file-upload" type="file" className="hidden" accept="image/*" onChange={handleFileChange} />
                                </label>

                                {previewUrl && (
                                    <Button className="mt-4" onClick={() => document.getElementById('file-upload')?.click()}>
                                        <Camera /> Cambiar foto
                                    </Button>
                                )}
                            </div>
                        </div>
                        <form onSubmit={form.handleSubmit(onSubmit)} className="p-6 md:p-8">
                            <div className="flex flex-col gap-6">
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
                                    name="phone_number"
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
                                    name="guardian_phone"
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
                                    name="high_school_name"
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

                                {isSubmitting ? (
                                    <Button disabled>
                                        <Loader2 className="mr-2 animate-spin" />
                                        Por favor, espera
                                    </Button>
                                ) : (
                                    <Button type="submit">Registrar Alumno</Button>
                                )}
                            </div>
                        </form>
                    </Form>
                </CardContent>
            </Card>
        </div>
    );
}
