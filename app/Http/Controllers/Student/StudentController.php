<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Person;
use App\Models\Student;

use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    //
    public function index() : Response
    {
        $students = Student::with('person')->get()->map(function ($student) {
            return [
                'student_id' => $student->student_id,
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
            'doi' => 'required|string|size:8|unique:persons,doi',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile_number' => 'required|string|size:9',
            'birth_date' => 'required|date',
            'guardian_mobile_number' => 'required|string|size:9',
            'graduated_high_school' => 'required|string|max:100',
        ]);

        $person = Person::firstOrCreate(
            ['doi' => $validated['doi']],
            [
                'first_names' => $validated['first_name'],
                'last_names' => $validated['last_name'],
                'mobile_number' => $validated['mobile_number'],
            ]
        );

        Student::create([
            'person_id' => $person->person_id,
            'birth_date' => $validated['birth_date'],
            'guardian_mobile_number' => $validated['guardian_mobile_number'],
            'graduated_high_school' => $validated['graduated_high_school'],
        ]);

        return redirect()->route('students.index')->with('success', 'Student enrolled successfully!');
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
}
