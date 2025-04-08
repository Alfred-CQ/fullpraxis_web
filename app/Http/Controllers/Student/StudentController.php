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
                'first_name' => $student->person->first_names,
                'last_name' => $student->person->last_names,
                'mobile_number' => $student->person->mobile_number,
                'birth_date' => $student->birth_date,
                'guardian_mobile_number' => $student->guardian_mobile_number,
                'graduated_high_school' => $student->graduated_high_school,
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile_number' => 'required|string|size:9',
            'birth_date' => 'required|date',
            'guardian_mobile_number' => 'required|string|size:9',
            'graduated_high_school' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $person = Person::firstOrCreate(
            ['doi' => $validated['doi']],
            [
                'first_names' => $validated['first_name'],
                'last_names' => $validated['last_name'],
                'mobile_number' => $validated['mobile_number'],
            ]
        );

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students/photos', 'public');
        }

        Student::create([
            'person_id' => $person->person_id,
            'birth_date' => $validated['birth_date'],
            'guardian_mobile_number' => $validated['guardian_mobile_number'],
            'graduated_high_school' => $validated['graduated_high_school'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('students.index')->with('success', 'Student enrolled successfully!');
    }

    public function edit($id)
    {
        $student = Student::with('person')->where('student_id', $id)->firstOrFail();

        return Inertia::render('students/edit', [
            'student' => [
                'student_id' => $student->student_id,
                'doi' => $student->person->doi,
                'first_name' => $student->person->first_names,
                'last_name' => $student->person->last_names,
                'mobile_number' => $student->person->mobile_number,
                'birth_date' => $student->birth_date,
                'guardian_mobile_number' => $student->guardian_mobile_number,
                'graduated_high_school' => $student->graduated_high_school,
                'photo_url' => $student->photo_path ? asset('storage/' . $student->photo_path) : null,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'doi' => 'required|string|size:8|unique:people,doi,' . $id . ',student_id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile_number' => 'required|string|size:9',
            'birth_date' => 'required|date',
            'guardian_mobile_number' => 'required|string|size:9',
            'graduated_high_school' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student = Student::where('student_id', $id)->firstOrFail();
        $person = $student->person;

        $person->update([
            'doi' => $validated['doi'],
            'first_names' => $validated['first_name'],
            'last_names' => $validated['last_name'],
            'mobile_number' => $validated['mobile_number'],
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students/photos', 'public');
            $student->update(['photo_path' => $photoPath]);
        }

        $student->update([
            'birth_date' => $validated['birth_date'],
            'guardian_mobile_number' => $validated['guardian_mobile_number'],
            'graduated_high_school' => $validated['graduated_high_school'],
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
        $student = Student::with('person')->where('student_id', $id)->firstOrFail();

        $data = [
            'doi' => $student->person->doi,
            'name' => $student->person->first_names . ' ' . $student->person->last_names,
            'mobile_number' => $student->person->mobile_number,
            'birth_date' => $student->birth_date,
            'guardian_mobile_number' => $student->guardian_mobile_number,
            'graduated_high_school' => $student->graduated_high_school,
        ];

        $pdf = Pdf::loadView('students.carnet', compact('data'));

        return $pdf->stream('carnet.pdf');
    }
}
