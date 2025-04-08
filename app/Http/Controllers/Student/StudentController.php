<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Person;
use App\Models\Student;

use Inertia\Inertia;
use Inertia\Response;

use Barryvdh\DomPDF\Facade\Pdf;

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

        $validated = $request->validate([
            'doi' => 'required',
            'first_names' => 'required|string|max:100',
            'last_names' => 'required|string|max:100',
            'phone_number' => 'required|string|size:9',
            'birth_date' => 'required|date',
            'guardian_phone' => 'required|string|size:9',
            'high_school_name' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


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

        return redirect()->route('students.index')->with('success', 'Estudiante actualizado correctamente.');
    }

    public function destroy($id)
    {
        $student = Student::where('student_id', $id)->firstOrFail();
        try {
            DB::beginTransaction();

            $person = Person::where('person_id', $student->person_id)->first();

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

    public function generateCarnetPdf($id)
    {
        $student = Student::with('person')->where('id', $id)->firstOrFail();

        $data = [
            'doi' => $student->person->doi,
            'name' => $student->person->first_names . ' ' . $student->person->last_names,
            'phone_number' => $student->person->phone_number,
            'birth_date' => $student->birth_date,
            'guardian_phone' => $student->guardian_phone,
            'high_school_name' => $student->high_school_name,
        ];

        $pdf = Pdf::loadView('students.carnet', compact('data'));

        return $pdf->stream('carnet.pdf');
    }
}
