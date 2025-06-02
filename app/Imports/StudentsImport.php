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

        $row['dni'] = isset($row['dni']) ? (string) $row['dni'] : null;
        $row['telefono'] = isset($row['telefono']) ? (string) $row['telefono'] : null;
        $row['telefono_del_tutor'] = isset($row['telefono_del_tutor']) ? (string) $row['telefono_del_tutor'] : null;
        $row['colegio_de_procedencia'] = isset($row['colegio_de_procedencia']) ? (string) $row['colegio_de_procedencia'] : null;
        foreach (['fecha_de_nacimiento', 'fecha_de_matricula', 'fecha_de_inicio', 'fecha_de_fin', 'fecha_de_vencimiento'] as $field) {
            if (!empty($row[$field])) {
                try {
                    $row[$field] = is_numeric($row[$field])
                        ? Carbon::instance(ExcelDate::excelToDateTimeObject($row[$field]))->format('Y-m-d')
                        : Carbon::createFromFormat('j/n/Y', $row[$field])->format('Y-m-d');
                } catch (\Exception $e) {
                    $this->errors[] = "Error en fila (DOI: {$row['dni']}): Formato de fecha inválido en el campo '{$field}'.";
                    return null; // O puedes continuar con el siguiente registro
                }
            }
        }

        
        if (empty(array_filter($row))) {
            return null;
        }
        $validator = Validator::make($row, [
            'dni' => 'required|string|size:8',
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'telefono' => 'nullable|string|size:9',
            'fecha_de_nacimiento' => 'nullable|date',
            'telefono_del_tutor' => 'nullable|string|size:9',
            'colegio_de_procedencia' => 'nullable|string|max:100',
            'fecha_de_matricula' => 'nullable|date',
            'ciclo_academico' => 'required|string|max:50',
            'fecha_de_inicio' => 'required|date',
            'fecha_de_fin' => 'required|date',
            'fecha_de_vencimiento' => 'required|date',
            'total_de_pago' => 'required|numeric',
            'estado_de_deuda' => 'required|in:Pagado,Pendiente,Vencido,No registrado',
            'area_de_estudio' => 'required|string|max:15',
            'turno' => 'nullable|in:Mañana,Tarde,Completo',
        ]); 

        if ($validator->fails()) {
            $doi = $row['dni'] ?? 'Sin DNI';
            $this->errors[] = "Error en fila (DOI: {$doi}): " . $validator->errors()->first();
            return null;
        }


        $shiftDB = match ($row['turno'] ?? null) {
            'Mañana'   => 'morning',
            'Tarde'    => 'afternoon',
            'Completo' => 'both',
            default    => null,
        };

        $paymentStatus = match ($row['estado_de_deuda'] ?? null) {
            'Pagado'     => 'paid',
            'Pendiente'  => 'pending',
            'Vencido'  => 'overdue',
            default    => 'pending', // ojo aquí, si no se especifica, se asume 'pending'
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
                    'start_date' => $row['fecha_de_inicio'],
                    'end_date' => $row['fecha_de_fin'],
                    'due_date' => $row['fecha_de_vencimiento'],
                    'total_payment' => $row['total_de_pago'],
                    'debt_status' => $paymentStatus,
                    'study_area' => $row['area_de_estudio'],
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
