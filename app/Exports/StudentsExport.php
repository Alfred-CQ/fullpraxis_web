<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Student::with(['person', 'person.enrollment'])->get();
    }

    public function map($student): array
    {
        $latestEnrollment = $student->person->enrollment->sortByDesc('enrollment_date')->first();

        $shiftTranslated = match ($latestEnrollment->shift ?? null) {
            'morning' => 'Mañana',
            'afternoon' => 'Tarde',
            'both' => 'Completo',
            default => 'No registrado',
        };

        return [
            $student->person->doi,
            $student->person->first_names,
            $student->person->last_names,
            $student->person->phone_number,
            $student->birth_date,
            $student->guardian_phone,
            $student->high_school_name,
            $student->photo_path ? 'Sí' : 'No',
            $student->carnet_path ? 'Sí' : 'No',
            $latestEnrollment->enrollment_date ?? 'No registrada',
            $shiftTranslated,
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
            '¿Tiene Foto?',
            '¿Tiene Carnet?',
            'Fecha de Matrícula',
            'Turno (Mañana/Tarde/Completo)',
        ];
    }
}
