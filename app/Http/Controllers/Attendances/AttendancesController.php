<?php

namespace App\Http\Controllers\Attendances;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Student\StudentController;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Person;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;

class AttendancesController extends Controller 
{
    public function attendanceRegisterApi(Request $request)
    {
        $validatedData = $request->validate([
            'doi' => 'required|string',
            'attendance_type' => 'required|in:Entry,Exit',
            'user' => 'required|string',
            'ip_address' => 'required|string',
        ]);

        $alumnoResponse = (new StudentController)->find_student($request);
        $responseData = json_decode($alumnoResponse->getContent(), true);

        if (!$responseData['success']) {
            return $alumnoResponse;
        }   

        $asistencia = Attendance::create([
            'person_id' => $responseData['alumno']['id'], 
            'attendance_type' => $request->attendance_type,
            'user' => $request->user,
            'ip_address' => $request->ip_address,
            'recorded_at' => Carbon::now('America/Lima'),
        ]);

        $fechaFormateada = Carbon::parse($asistencia->created_at)
        ->setTimezone('America/Lima')
        ->format('d-m-Y H:i:s');


        return response()->json([
            'success' => true,
            'message' => 'Asistencia registrada correctamente',
            'data' => [
                'alumno' => [
                    'id' => $responseData['alumno']['id'],
                    'nombre_completo' => $responseData['alumno']['first_names'] . ' ' . $responseData['alumno']['last_names'],
                    'dni' => $responseData['alumno']['doi'],
                    'numero_apoderado' => $responseData['alumno']['guardian_phone'],
                ],
                'asistencia' => [
                    'id' => $asistencia->id,
                    'tipo' => $asistencia->attendance_type,
                    'fecha_registro' => $fechaFormateada,
                    'hora_registro' => Carbon::now('America/Lima')->format('H:i:s'), 
                ],
                'matricula' => [
                    'dias_restantes' => $responseData['matricula']['dias_restantes'],
                    'status' => $responseData['matricula']['status_deuda'],
                ],
                'mensaje' => 'La asistencia ha sido registrada correctamente'
            ]
        ]);        
    }

    public function dailyAttendanceReportPdf()
    {
        $hoy = Carbon::now('America/Lima')->format('Y-m-d');

        // Obtenemos asistencias del día actual con datos del alumno
        $attendances = Attendance::with('person')
            ->whereDate('recorded_at', $hoy)
            ->orderBy('recorded_at', 'asc')
            ->get()
            ->groupBy('person.id');

        // Estructuramos los datos por persona
        $data = $attendances->map(function ($asistencias, $personId) {
            $persona = $asistencias->first()->person;
            return [
                'nombre_completo' => $persona->first_names . ' ' . $persona->last_names,
                'doi' => $persona->doi,
                'asistencias' => $asistencias->map(function ($a) {
                    return [
                        'tipo' => $a->attendance_type,
                        'hora' => Carbon::parse($a->recorded_at)->format('H:i:s'),
                    ];
                })->values()->toArray(),
            ];
        });

        // Cargar vista y generar PDF
        $pdf = Pdf::loadView('attendances.daily-report-pdf', ['data' => $data, 'fecha' => $hoy]);

        return $pdf->stream("reporte-asistencia-{$hoy}.pdf");
    }

}