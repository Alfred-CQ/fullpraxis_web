<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AcademicTerm;

use Inertia\Inertia;
use Inertia\Response;

class AcademicTermController extends Controller
{
    public function index(): Response
    {
        $academicTerms = AcademicTerm::all(['id', 'name', 'start_date', 'end_date', 'monthly_cost', 'enrollment_cost']);

        return Inertia::render('academicTerms/index', [
            'academicTerms' => $academicTerms,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('academicTerms/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'monthly_cost' => 'required|numeric|min:0',
            'enrollment_cost' => 'required|numeric|min:0',
        ]);

        AcademicTerm::create($validated);

        return redirect()->route('academic-terms.index')->with('success', 'Academic term created successfully!');
    }
}
