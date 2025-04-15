<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Enrollment;
use App\Models\Person;
use App\Models\Student;
use App\Models\AcademicTerm;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;

class EnrollmentController extends Controller
{
    public function index(): Response
    {
        $enrollments = Enrollment::with(['person', 'academicTerm'])
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'study_area' => $enrollment->study_area,
                    'enrollment_date' => \Carbon\Carbon::parse($enrollment->enrollment_date)->format('Y-m-d'),
                    'start_date' => \Carbon\Carbon::parse($enrollment->start_date)->format('Y-m-d'),
                    'end_date' => \Carbon\Carbon::parse($enrollment->end_date)->format('Y-m-d'),
                    'due_date' => \Carbon\Carbon::parse($enrollment->due_date)->format('Y-m-d'),
                    'total_payment' => $enrollment->total_payment,
                    'debt_status' => $enrollment->debt_status,
                    'student_doi' => $enrollment->person->doi,
                    'academic_term_name' => $enrollment->academicTerm->name,
                    'shift' => $enrollment->shift,
                ];
            });


         return Inertia::render('enrollments/index', [
            'enrollments' => $enrollments,
        ]);
    }

    public function create(): Response
    {
        $academicTerms = AcademicTerm::all(['id', 'name']);

        return Inertia::render('enrollments/create', [
            'academic_terms' => $academicTerms,
        ]);
    }

    public function store(StoreEnrollmentRequest $request)
    {
        try {
            $validated = $request->validated();

            $person = Person::where('doi', $validated['doi'])->first();
            Enrollment::create([
                'person_id' => $person->id,
                'academic_term_id' => $validated['academic_term_id'],
                'study_area' => $validated['study_area'],
                'enrollment_date' => $validated['enrollment_date'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'due_date' => $validated['due_date'],
                'total_payment' => $validated['total_payment'],
                'debt_status' => $validated['debt_status'],
                'shift' => $validated['shift'],
            ]);
            

            return redirect()->route('enrollments.index')->with('flash', [
                'success' => 'Matrícula registrada correctamente.',
                'description' => 'La matrícula ha sido registrada exitosamente.',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('flash', [
                'error' => 'Error al matrícular estudiante.',
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id): Response
    {
        $enrollment = Enrollment::with(['person', 'academicTerm'])->findOrFail($id);
        $academicTerms = AcademicTerm::all(['id', 'name']);

        return Inertia::render('enrollments/edit', [
            'enrollment' => [
                'id' => $enrollment->id,
                'doi' => $enrollment->person->doi,
                'study_area' => $enrollment->study_area,
                'enrollment_date' => $enrollment->enrollment_date,
                'start_date' => $enrollment->start_date,
                'end_date' => $enrollment->end_date,
                'due_date' => $enrollment->due_date,
                'total_payment' => $enrollment->total_payment,
                'debt_status' => $enrollment->debt_status,
                'academic_term_id' => $enrollment->academic_term_id,
                'shift' => $enrollment->shift,  
            ],
            'academic_terms' => $academicTerms,
        ]);
    }

    public function update(UpdateEnrollmentRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $person = Person::where('doi', $validated['doi'])->first();
    
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->update([
                'person_id' => $person->id,
                'academic_term_id' => $validated['academic_term_id'],
                'study_area' => $validated['study_area'],
                'enrollment_date' => $validated['enrollment_date'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'due_date' => $validated['due_date'],
                'total_payment' => $validated['total_payment'],
                'debt_status' => $validated['debt_status'],
                'shift' => $validated['shift'],
            ]);
    
            return redirect()->route('enrollments.index')->with('flash', [
                'success' => 'Matrícula actualizada correctamente.',
                'description' => 'La matrícula ha sido actualizada exitosamente.',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('flash', [
                'error' => 'Error al actualizar matrícula.',
                'description' => $e->getMessage(),
            ]);
        }
    }
}
