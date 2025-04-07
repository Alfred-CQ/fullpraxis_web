<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Enrolment;
use App\Models\Person;
use App\Models\Student;
use App\Models\Season;

use Inertia\Inertia;
use Inertia\Response;

class EnrolmentController extends Controller
{
    public function index(): Response
    {
        $enrolments = Enrolment::with(['student.person', 'season'])
            ->get()
            ->map(function ($enrolment) {
                return [
                    'enrolment_id' => $enrolment->enrolment_id,
                    'enrolment_code' => $enrolment->enrolment_code,
                    'study_area' => $enrolment->study_area,
                    'enrolment_date' => $enrolment->enrolment_date,
                    'start_date' => $enrolment->start_date,
                    'end_date' => $enrolment->end_date,
                    'due_date' => $enrolment->due_date,
                    'total_payment' => $enrolment->total_payment,
                    'debt_status' => $enrolment->debt_status,
                    'student_doi' => $enrolment->student->person->doi,
                    'season_name' => $enrolment->season->name,
                ];
            });


        return Inertia::render('enrolments/index', [
            'enrolments' => $enrolments,
        ]);
    }

    public function create(): Response
    {
        $seasons = Season::all(['season_id', 'name'])->map(function ($season) {
            return [
                'season_id' => $season->season_id,
                'name' => $season->name,
            ];
        });

        return Inertia::render('enrolments/create', [
            'seasons' => $seasons,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doi' => 'required|string|size:8',
            'season_id' => 'required',
            'enrolment_code' => 'required|string',
            'study_area' => 'required|string|max:255',
            'enrolment_date' => 'required|date',
            'start_date' => 'required|date|',
            'end_date' => 'required|date|',
            'due_date' => 'required|date|',
            'total_payment' => 'required|numeric|min:0',
            'debt_status' => 'required|string',
        ]);


        $person = Person::where('doi', $validated['doi'])->firstOrFail();
        $student = Student::where('person_id', $person->person_id)->firstOrFail();

        Enrolment::create([
            'student_id' => $student->student_id,
            'season_id' => $validated['season_id'],
            'enrolment_code' => $validated['enrolment_code'],
            'study_area' => $validated['study_area'],
            'enrolment_date' => $validated['enrolment_date'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'due_date' => $validated['due_date'],
            'total_payment' => $validated['total_payment'],
            'debt_status' => $validated['debt_status'],
        ]);

        return redirect()->route('enrolments.index')->with('success', 'Enrolment created successfully!');
    }

    public function edit($id): Response
    {
        $enrolment = Enrolment::with(['student.person', 'season'])->findOrFail($id);

        return Inertia::render('enrolments/edit', [
            'enrolment' => [
                'enrolment_id' => $enrolment->enrolment_id,
                'enrolment_code' => $enrolment->enrolment_code,
                'study_area' => $enrolment->study_area,
                'enrolment_date' => $enrolment->enrolment_date,
                'start_date' => $enrolment->start_date,
                'end_date' => $enrolment->end_date,
                'due_date' => $enrolment->due_date,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'enrolment_code' => 'required|string|max:8|unique:enrolments,enrolment_code,' . $id . ',enrolment_id',
            'study_area' => 'required|string|max:255',
            'enrolment_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'due_date' => 'required|date',
        ]);

        $enrolment = Enrolment::findOrFail($id);
        $enrolment->update($validated);

        return redirect()->route('enrolments.index')->with('success', 'Enrolment updated successfully!');
    }
}
