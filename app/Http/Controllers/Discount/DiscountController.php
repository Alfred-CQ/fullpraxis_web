<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Discount;

use Inertia\Inertia;
use Inertia\Response;

class DiscountController extends Controller
{
    public function index(): Response
    {
        $discounts = Discount::all(['id', 'name', 'monthly_discount', 'enrollment_discount', 'description']);

        return Inertia::render('discounts/index', [
            'discounts' => $discounts,
        ]);
    }


    public function create(): Response
    {
        return Inertia::render('discounts/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'monthly_discount' => 'nullable|numeric|min:0',
            'enrollment_discount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        Discount::create($validated);

        return redirect()->route('discounts.index')->with('success', 'Discount created successfully!');
    }
}
