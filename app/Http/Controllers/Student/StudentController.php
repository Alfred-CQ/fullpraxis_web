<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Person;
use App\Models\Student;

use Inertia\Inertia;
use Inertia\Response;

use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Storage;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    //
    public function index(): Response
    {

        $students = Student::with('person')->get()->map(function ($student) {
            return [
                'student_id' => $student->id,
                'doi' => $student->person->doi,
                'first_names' => $student->person->first_names,
                'last_names' => $student->person->last_names,
                'phone_number' => $student->person->phone_number,
                'birth_date' => $student->birth_date,
                'guardian_phone' => $student->guardian_phone,
                'high_school_name' => $student->high_school_name,
                'created_at' => $student->created_at,
            ];
        });

        return Inertia::render('students/index', [
            'students' => $students,
            'flash' => session('flash'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('students/enroll', ['flash' => session('flash')]);
    }

    public function store(StoreStudentRequest $request)
    {
        try {
            $validated = $request->validated();

            $person = Person::firstOrCreate(
                ['doi' => $validated['doi']],
                [
                    'first_names' => $validated['first_names'],
                    'last_names' => $validated['last_names'],
                    'phone_number' => $validated['phone_number'],
                ]
            );

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('students/photos', 'public');
            }

            $student = Student::create([
                'person_id' => $person->id,
                'birth_date' => $validated['birth_date'],
                'guardian_phone' => $validated['guardian_phone'],
                'high_school_name' => $validated['high_school_name'],
                'photo_path' => $photoPath,
            ]);

            $carnetImage = $this->generateCarnetImage($student);
            $carnetPath = 'students/carnets/' . $student->id . '.png';
            Storage::disk('public')->put($carnetPath, $carnetImage);
            $student->update(['carnet_path' => $carnetPath]);

            return redirect()->route('students.index')->with('flash', [
                'success' => 'Estudiante registrado correctamente.',
                'description' => 'El estudiante ha sido registrado exitosamente.',  
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('flash', [
                'error' => 'Error al registrar el estudiante.',
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $student = Student::with('person')->where('id', $id)->firstOrFail();

        return Inertia::render('students/edit', [
            'student' => [
                'student_id' => $student->id,
                'doi' => $student->person->doi,
                'first_names' => $student->person->first_names,
                'last_names' => $student->person->last_names,
                'phone_number' => $student->person->phone_number,
                'birth_date' => $student->birth_date,
                'guardian_phone' => $student->guardian_phone,
                'high_school_name' => $student->high_school_name,
                'photo_path' => $student->photo_path ? asset('storage/' . $student->photo_path) : null,
            ],
        ]);
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        try {
        $validated = $request->validated();

        $student = Student::where('id', $id)->firstOrFail();
        $person = $student->person;

        $person->update([
            'doi' => $validated['doi'],
                'first_names' => $validated['first_names'],
                'last_names' => $validated['last_names'],
                'phone_number' => $validated['phone_number'],
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students/photos', 'public');
            $student->update(['photo_path' => $photoPath]);
        }

        $student->update([
            'birth_date' => $validated['birth_date'],
            'guardian_phone' => $validated['guardian_phone'],
            'high_school_name' => $validated['high_school_name'],
        ]);

        $carnetImage = $this->generateCarnetImage($student);
        $carnetPath = 'students/carnets/' . $student->id . '.png';
        Storage::disk('public')->put($carnetPath, $carnetImage);
        $student->update(['carnet_path' => $carnetPath]);

        return redirect()->route('students.index')->with('flash', [
            'success' => 'Estudiante actualizado correctamente.',
            'description' => 'El estudiante ha sido actualizado exitosamente.',
        ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('flash', [
                'error' => 'Error al actualizar el estudiante.',
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        $student = Student::where('id', $id)->firstOrFail();
        try {
            DB::beginTransaction();

            $person = Person::where('id', $student->person_id)->first();

            $student->delete();

            if ($person) {
                $person->delete();
            }

            DB::commit();

            return redirect()->route('students.index')->with('success', 'Estudiante y persona asociados eliminados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al intentar eliminar el estudiante y la persona asociada.']);
        }
    }

    private function generateCarnetImage($student)
    {
        $manager = new ImageManager((new Driver()));
        $image = $manager->read(public_path('carnet_fullpraxis.png'));

        $latestEnrollment = $student->person->enrollment()->latest('enrollment_date')->first();

        if ($latestEnrollment == null) {
            throw new \Exception('No se encontró la matrícula más reciente para el estudiante.');
        }


        $first_names = $student->person->first_names;
        $last_names = $student->person->last_names;
        $doi = $student->person->doi;
        $photo_path = $student->photo_path ? storage_path('app/public/' . $student->photo_path) : null;
        $start_date = Carbon::parse($latestEnrollment->start_date)->format('d    m     y');
        $shift = $latestEnrollment->shift;

        $shiftTranslations = [
            'morning' => '  Mañana',
            'afternoon' => '    Tarde',
            'both' => 'Completo'
        ];

        $translatedShift = $shiftTranslations[strtolower($shift)] ?? $shift;

        $image->text($last_names, 280, 164, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($first_names, 280, 260, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($doi, 490, 415, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($start_date, 818, 410, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($translatedShift, 70, 415, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        if(file_exists($photo_path)) {
            $photo = $manager->read($photo_path);
            $photo->resize(180, 220);

            $image->place($photo, 'top-left', 60, 130);
        }

        $qrCode = QrCode::create($doi)
            ->setSize(220)
            ->setMargin(0);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodePng = $result->getString();

        $qrImage = $manager->read($qrCodePng);
        $image->place($qrImage, 'top-left', 1310, 202);

        return $image->encode()->__toString();
    }

    public function generateCarnetPdf($id)
    {

        $manager = new ImageManager((new Driver()));

        $image = $manager->read(public_path('carnet_fullpraxis.png'));

        $student = Student::with('person')->where('id', $id)->firstOrFail();
        $latestEnrollment = $student->person->enrollment()->latest('enrollment_date')->first();

        // Extract important data
        $first_names = $student->person->first_names;
        $last_names = $student->person->last_names;
        $doi = $student->person->doi;
        $photo_path = $student->photo_path ? storage_path('app/public/' . $student->photo_path) : null;
        $start_date = Carbon::parse($latestEnrollment->start_date)->format('d    m     y');
        $shift = $latestEnrollment->shift;

        $shiftTranslations = [
            'morning' => '  Mañana',
            'afternoon' => '    Tarde',
            'both' => 'Completo'
        ];

        $translatedShift = $shiftTranslations[strtolower($shift)] ?? $shift;

        $image->text($last_names, 280, 164, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($first_names, 280, 260, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($doi, 490, 415, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($start_date, 818, 410, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $image->text($translatedShift, 70, 415, function ($font) {
            $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        if(file_exists($photo_path)) {
            $photo = $manager->read($photo_path);
            $photo->resize(180, 220);

            $image->place($photo, 'top-left', 60, 130);
        }

        $qrCode = QrCode::create($doi)
            ->setSize(220)
            ->setMargin(0);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodePng = $result->getString();

        $qrImage = $manager->read($qrCodePng);
        $image->place($qrImage, 'top-left', 1310, 202);

        $imageData = $image->encode()->__toString();

        $pdf = Pdf::loadView('students.carnet', ['imageData' => $imageData]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('carnet.pdf');

        //return response($image->toJpeg())->header('Content-Type', 'image/jpeg');
    }

    public function attendanceReportPdf($id)
    {
        $student = Student::with('person.attendances')->where('id', $id)->firstOrFail();

        $url = 'https://validez/' . $student->id;
        $qrPath = public_path('qrs/validez-' . $student->id . '.png');

        if (!file_exists($qrPath)) {
            $qr = QrCode::create($url)->setSize(100)->setMargin(5);
            $writer = new PngWriter();
            $result = $writer->write($qr);
            file_put_contents($qrPath, $result->getString());
        }

        $attendancesByDay = $student->person->attendances
            ->sortBy('recorded_at')
            ->groupBy(function ($attendance) {
                return \Carbon\Carbon::parse($attendance->recorded_at)->format('Y-m-d');
            });

        $data = [
            'student' => [
                'student_id' => $student->id,
                'doi' => $student->person->doi,
                'first_names' => $student->person->first_names,
                'last_names' => $student->person->last_names,
                'phone_number' => $student->person->phone_number,
                'photo_path' => $student->photo_path,
            ],
            'attendances' => $attendancesByDay,
        ];


        $pdf = Pdf::loadView('students.attendance-report-pdf', compact('data'));

        return $pdf->stream('attendance-report.pdf');
    }

    public function find_student(Request $request)
    {
        $request->validate([
            'doi' => 'required|string|max:8',
        ]);

        $doi = $request->input('doi');

        $persona = Person::with(['student', 'enrollment' => function($query) {
            $query->latest('enrollment_date');
        }])->where('doi', $doi)->first();

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Alumno no encontrado',
            ], 404);
        }

        $matricula = $persona->enrollment->first();

        $diasRestantes = null;
        if ($matricula && $matricula->due_date) {
            $hoy = Carbon::now('America/Lima')->startOfDay();
            $fechaVencimiento = Carbon::parse($matricula->due_date)
                ->timezone('America/Lima')
                ->startOfDay();

            $diasRestantes = $hoy->diffInDays($fechaVencimiento, false);
        }

        return response()->json([
            'success' => true,
            'alumno' => [
                'id' => $persona->id,
                'first_names' => $persona->first_names,
                'last_names' => $persona->last_names,
                'doi' => $persona->doi,
                'guardian_phone' => $persona->student->guardian_phone ?? 'No disponible',
            ],
            'matricula' => [
                'dias_restantes' => $diasRestantes,
                'status_deuda' => $matricula ? $matricula->debt_status : 'Sin matrícula',
            ],
        ]);
    }

    public function calendar($id): Response
    {
        $student = Student::with('person')->where('id', $id)->firstOrFail();
        $attendances = $student->person->attendances()
            ->select('recorded_at', 'attendance_type')
            ->orderBy('recorded_at', 'desc')
            ->get()
            ->map(function ($attendance) {
                return [
                    'recorded_at' => $attendance->recorded_at,
                    'attendance_type' => $attendance->attendance_type,
                ];
            });

        return Inertia::render('students/calendar', [
            'student' => [
                'student_id' => $student->id,
                'first_names' => $student->person->first_names,
                'last_names' => $student->person->last_names,
            ],
            'attendances' => $attendances,
        ]);
    }

    public function generateSelectedCarnetsPdf(Request $request)
    {
        $selectedStudents = $request->input('ids', []);
        $students = Student::with('person')->whereIn('id', $selectedStudents)->get();

        $studentsWithErrors = [];
        $data = [];

        try {
            foreach ($students as $student) {
                $latestEnrollment = $student->person->enrollment()->latest('enrollment_date')->first();
                if (!$latestEnrollment) {
                    $studentsWithErrors[] = $student->person->doi;
                    continue;
                }

                $carnetPath = 'students/carnets/' . $student->id . '.png';
                if (!$student->carnet_path || !Storage::disk('public')->exists($student->carnet_path)) {
                    $imageData = $this->generateCarnetImage($student);
                    Storage::disk('public')->put($carnetPath, $imageData);
                    $student->update(['carnet_path' => $carnetPath]);   
                }

                $imageData = Storage::disk('public')->get($carnetPath);
                $data[] = ['imageData' => $imageData];
            }

            if (!empty($studentsWithErrors)) {
                return redirect()->back()->with('flash', [
                    'error' => 'Estudiantes sin matrícula',
                    'description' => implode(', ', $studentsWithErrors),
                ]);
            }

            $pdf = Pdf::loadView('students.carnet_batch', ['students' => $data]);
            return $pdf->stream('carnets.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('flash', [
                'error' => 'Error al generar carnets',
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new StudentsExport, 'estudiantes.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        $import = new StudentsImport;
        Excel::import($import, $request->file('file'));

        //dd($import);
        if (!empty($import->getErrors())) {
            return back()->with('flash', [
                'error' => 'Errores al importar estudiantes',
                'description' => implode(' ', $import->getErrors()),
            ]);
        }

        return back()->with('flash', [
            'success' => 'Estudiantes importados correctamente',
            'description' => 'Todos los estudiantes han sido importados exitosamente.',
        ]);
    }   
}
