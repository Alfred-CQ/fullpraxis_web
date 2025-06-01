<?php

namespace App\Imports;

use App\Models\Person;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\AcademicTerm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class StudentsImport implements ToModel, WithHeadingRow
{
    private $errors = [];

    public function model(array $row)
    {

        $row['dni'] = (string) $row['dni'] ?? null;
        $row['telefono'] = isset($row['telefono']) ? (string) $row['telefono'] : null;
        $row['telefono_del_tutor'] = isset($row['telefono_del_tutor']) ? (string) $row['telefono_del_tutor'] : null;
        
        foreach (['fecha_de_nacimiento', 'fecha_de_matricula', 'start_date', 'end_date', 'due_date'] as $field) {
            if (!empty($row[$field])) {
                try {
                    $row[$field] = is_numeric($row[$field])
                        ? Carbon::instance(ExcelDate::excelToDateTimeObject($row[$field]))->format('Y-m-d')
                        : Carbon::parse($row[$field])->format('Y-m-d');
                } catch (\Exception $e) {
                    $this->errors[] = "Error en fila (DOI: {$row['dni']}): Formato de fecha inválido en el campo '{$field}'.";
                    return null; // Skip this row if date parsing fails
                }
            }
        }

        $validator = Validator::make($row, [
            'dni' => 'required|string|size:8|unique:people,doi',
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'telefono' => 'nullable|string|size:9',
            'fecha_de_nacimiento' => 'nullable|date',
            'telefono_del_tutor' => 'nullable|string|size:9',
            'colegio_de_procedencia' => 'nullable|string|max:100',
            'fecha_de_matricula' => 'nullable|date',
            'ciclo_academico' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'due_date' => 'required|date',
            'total_payment' => 'required|numeric',
            'debt_status' => 'required|in:Pagado,Pendiente,Vencido',
            'study_area' => 'required|string|max:15',
            'turno' => 'nullable|in:Mañana,Tarde,Completo',
        ]); 


        if ($validator->fails()) {
            $this->errors[] = "Error en fila (DOI: {$row['dni']}): " . $validator->errors()->first();
            return null;
        }

        $shiftDB = match ($row['turno'] ?? null) {
            'Mañana'   => 'morning',
            'Tarde'    => 'afternoon',
            'Completo' => 'both',
            default    => null,
        };

        $paymentStatus = match ($row['debt_status'] ?? null) {
            'Pagado'     => 'paid',
            'Pendiente'  => 'pending',
            'Vencido'  => 'overdue',
            default    => null,
        };

        $person = Person::updateOrCreate(
            ['doi' => $row['dni']],
            [
                'first_names' => $row['nombres'],
                'last_names'  => $row['apellidos'],
                'phone_number' => $row['telefono'],
                'person_type' => 'Student',
            ]
        );

        $student = Student::updateOrCreate(
            ['person_id' => $person->id],
            [
                'birth_date' => $row['fecha_de_nacimiento'] ? Carbon::parse($row['fecha_de_nacimiento']) : null,
                'guardian_phone' => $row['telefono_del_tutor'],
                'high_school_name' => $row['colegio_de_procedencia'],
            ]
        );


        $academicTerm = AcademicTerm::firstOrCreate(
            ['name' => $row['ciclo_academico']],
            ['start_date' => now(), 'end_date' => now()->addMonths(6)]
        );

        if (!empty($row['fecha_de_matricula'])) {
            Enrollment::updateOrCreate(
                [
                    'person_id' => $person->id,
                    'academic_term_id' => $academicTerm->id,
                ],
                [
                    'enrollment_date' => $row['fecha_de_matricula'],
                    'start_date' => $row['start_date'],
                    'end_date' => $row['end_date'],
                    'due_date' => $row['due_date'],
                    'total_payment' => $row['total_payment'],
                    'debt_status' => $paymentStatus,
                    'study_area' => $row['study_area'],
                    'shift' => $shiftDB,
                ]
            );
        }
        //dd($this->errors);
        return $student;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
