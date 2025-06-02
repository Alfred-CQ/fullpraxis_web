<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Carbon\Carbon;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Student::with([
            'person', 
            'person.enrollment.academicTerm'
        ])->get();
    }

    public function map($student): array
    {
        $enrollment = $student->person->enrollment->sortByDesc('enrollment_date')->first();

        $shiftTranslated = match ($enrollment->shift ?? null) {
            'morning' => 'Mañana',
            'afternoon' => 'Tarde',
            'both' => 'Completo',
            default => 'No registrado',
        };
        $paymentStatus = match ($enrollment->debt_status ?? null) {
            'Paid' => 'Pagado',
            'Pending' => 'Pendiente',
            'Overdue' => 'Vencido',
            default => 'No registrado',
        };

        
        return [
            'dni' => $student->person->doi,
            'nombres' => $student->person->first_names,
            'apellidos' => $student->person->last_names,
            'telefono' => $student->person->phone_number,
            'fecha_de_nacimiento' => $student->birth_date ? Carbon::parse($student->birth_date)->format('d/m/Y') : null,

            'telefono_del_tutor' => $student->guardian_phone,
            'colegio_de_procedencia' => $student->high_school_name,

            'fecha_de_matricula' => $enrollment?->enrollment_date ? Carbon::parse($enrollment->enrollment_date)->format('d/m/Y') : "No Matriculado",
            'ciclo_academico' => $enrollment->academicTerm->name ?? "No Matriculado",
            'start_date' => $enrollment?->start_date ? Carbon::parse($enrollment->start_date)->format('d/m/Y') : "No Matriculado",
            'end_date' => $enrollment?->end_date ? Carbon::parse($enrollment->end_date)->format('d/m/Y') : "No Matriculado",
            'due_date' => $enrollment?->due_date ? Carbon::parse($enrollment->due_date)->format('d/m/Y') : "No Matriculado",
            'total_payment' => $enrollment?->total_payment,
            'debt_status' => $paymentStatus,
            'study_area' => $enrollment?->study_area,
            'turno' => $shiftTranslated,

            'tiene_foto' => $student->photo_path ? 'Sí' : 'No',
            'tiene_carnet' => $student->carnet_path ? 'Sí' : 'No',
        ];
    }

    public function headings(): array
    {
        return [
            'DNI',
            'Nombres',
            'Apellidos',
            'Teléfono',
            'Fecha de Nacimiento',
            'Teléfono del Tutor',
            'Colegio de Procedencia',
            'Fecha de Matrícula',
            'Ciclo Académico',
            'Fecha de Inicio',
            'Fecha de Fin',
            'Fecha de Vencimiento',
            'Total de Pago',
            'Estado de Deuda',
            'Área de Estudio',
            'Turno',
            '¿Tiene Foto?',
            '¿Tiene Carnet?',
        ];
    }
}
