<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Person;
use App\Models\Student;

use Inertia\Inertia;
use Inertia\Response;

use Illuminate\Support\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\ImageManager;    
use Intervention\Image\Drivers\Gd\Driver;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('students/enroll');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doi' => 'required|string|size:8|unique:people,doi',
            'first_names' => 'required|string|max:100',
            'last_names' => 'required|string|max:100',
            'phone_number' => 'required|string|size:9',
            'birth_date' => 'required|date',
            'guardian_phone' => 'required|string|size:9',
            'high_school_name' => 'required|string|max:100',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);


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

        //    dd($person->id);

        Student::create([
            'person_id' => $person->id,
            'birth_date' => $validated['birth_date'],
            'guardian_phone' => $validated['guardian_phone'],
            'high_school_name' => $validated['high_school_name'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('students.index')->with('success', 'Student enrolled successfully!');
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

    public function update(Request $request, $id)
    {
        $student = Student::with('person')->where('id', $id)->firstOrFail();
        $person = $student->person;
        
        $validated = $request->validate([
            'doi' => 'required|string|size:8|unique:people,doi,' . $person->id,
            'first_names' => 'required|string|max:100',
            'last_names' => 'required|string|max:100',
            'phone_number' => 'nullable|string|size:9',
            'birth_date' => 'required|date',
            'guardian_phone' => 'required|string|size:9',
            'high_school_name' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student = Student::where('id', $id)->firstOrFail();
        $person = $student->person;

        $person->update([
            'doi' => $validated['doi'],
            'first_names' => $validated['first_names'],  // Corregido
            'last_names' => $validated['last_names'],    // Corregido
            'phone_number' => $validated['phone_number'], // Corregido
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

        return redirect()->route('students.index')->with('success', 'Estudiante actualizado correctamente.');
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
    /*
    public function generateCarnetBatchPdf()
    {
        $students = Student::with('person')->get();
        $imageDataArray = []; // Aquí almacenaremos todas las imágenes en base64

        foreach ($students as $student) {
            $manager = new ImageManager((new Driver()));
            $image = $manager->read(public_path('carnet_fullpraxis.png'));

            $latestEnrollment = $student->person->enrollment()->latest('enrollment_date')->first();

            // Extraer datos
            $first_names = $student->person->first_names;
            $last_names = $student->person->last_names;
            $doi = $student->person->doi;
            $photo_path = $student->photo_path ? storage_path('app/public/' . $student->photo_path) : null;
            $start_date = Carbon::parse($latestEnrollment->start_date)->format('d    m     y');

            // Agregar texto (igual que en tu función individual)
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

            // Procesar foto y QR (igual que en tu función individual)
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

            // Convertir a base64 directamente sin archivos temporales
            $imageDataArray[] = $image->encode()->__toString();
        }

        // Generar el PDF con todas las imágenes
        $pdf = Pdf::loadView('students.carnet_batch', ['imageDataArray' => $imageDataArray]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('carnets.pdf');
    }
    */

    public function generateAllCarnetsPdf()
    {
        $students = Student::with('person')->get();

        $data = [];
        foreach ($students as $student) {
            $image = $this->generateCarnetImage($student);
            $data[] = ['imageData' => $image]; 
        }

        $pdf = Pdf::loadView('students.carnet_batch', ['students' => $data]);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('carnets.pdf');
    }

    private function generateCarnetImage($student)
    {
        $manager = new ImageManager((new Driver()));
        $image = $manager->read(public_path('carnet_fullpraxis.png'));
        
        $latestEnrollment = $student->person->enrollment()->latest('enrollment_date')->first();

        $first_names = $student->person->first_names;
        $last_names = $student->person->last_names;
        $doi = $student->person->doi;
        $photo_path = $student->photo_path ? storage_path('app/public/' . $student->photo_path) : null;
        $start_date = Carbon::parse($latestEnrollment->start_date)->format('d    m     y');
        

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
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('carnet.pdf');
        
        //return response($image->toJpeg())->header('Content-Type', 'image/jpeg');
    }

    public function attendanceReportPdf($id)
    {
        $student = Student::with('person.attendances')->where('id', $id)->firstOrFail();

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
        $diasRestantes = $matricula ? Carbon::now()->diffInDays($matricula->fecha_vencimiento, false) : null;
    
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



}
