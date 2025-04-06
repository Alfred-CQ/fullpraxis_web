<?php

namespace App\Http\Controllers\Season;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Season;

use Inertia\Inertia;
use Inertia\Response;

class SeasonController extends Controller
{
    public function index(): Response
    {
        $seasons = Season::all(['season_id', 'name', 'start_date', 'end_date', 'monthly_cost', 'enrollment_cost']);

        return Inertia::render('seasons/index', [
            'seasons' => $seasons,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('seasons/create');
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



        Season::create($validated);

        return redirect()->route('seasons.index')->with('success', 'Season created successfully!');
    }
}
