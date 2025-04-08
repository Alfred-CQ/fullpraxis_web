<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;

use App\Models\Receipt;
use App\Models\Enrollment;
use App\Models\Discount;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Inertia\Response;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the receipts.
     */
    public function index(): Response
    {
        $receipts = Receipt::with(['enrollment', 'discount'])
        ->get()
        ->map(function ($receipt) {
            return [
                'id' => $receipt->id,
                'receipt_code' => $receipt->receipt_code,
                'payment_date' => $receipt->payment_date,
                'enrollment_payment' => $receipt->enrollment_payment,
                'monthly_payment' => $receipt->monthly_payment,
                'notes' => $receipt->notes,

                'discount' => $receipt->discount->name ?? null,
            ];
        });

    return Inertia::render('receipts/index', [
        'receipts' => $receipts,
    ]);
    }

    public function create(): Response
    {
        $enrollments = Enrollment::all(['id', 'study_area']);
        $discounts = Discount::all(['id', 'name']);

        return Inertia::render('receipts/create', [
            'enrollments' => $enrollments,
            'discounts' => $discounts,
        ]);
    }


    /**
     * Store a newly created receipt in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doi' => 'required|exists:people,doi',
            'discount_id' => 'nullable|exists:discounts,id',
            'receipt_code' => 'required|string|max:10|unique:receipts,receipt_code',
            'payment_date' => 'required|date',
            'enrollment_payment' => 'required|numeric|min:0',
            'monthly_payment' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);


        $enrollment = Enrollment::whereHas('person', function ($query) use ($validated) {
            $query->where('doi', $validated['doi']);
        })->latest()->first();

        if (!$enrollment) {
            return back()->withErrors(['doi' => 'No se encontró una matrícula asociada a este DNI.']);
        }

       Receipt::create([
            'enrollment_id' => $enrollment->id,
            'discount_id' => $validated['discount_id'],
            'receipt_code' => $validated['receipt_code'],
            'payment_date' => $validated['payment_date'],
            'enrollment_payment' => $validated['enrollment_payment'],
            'monthly_payment' => $validated['monthly_payment'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('receipts.index')->with('success', 'Recibo creado exitosamente.');
    }
    /**
     * Update the specified receipt in storage.
     */
    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'discount_id' => 'nullable|exists:discounts,id',
            'receipt_code' => 'required|string|max:10|unique:receipts,receipt_code,' . $receipt->id,
            'payment_date' => 'required|date',
            'enrollment_payment' => 'required|numeric|min:0',
            'monthly_payment' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $receipt->update($validated);

        return redirect()->route('receipts.index')->with('success', 'Recibo actualizado exitosamente.');
    }

    /**
     * Remove the specified receipt from storage.
     */
    public function destroy(Receipt $receipt)
    {
        $receipt->delete();

        return redirect()->route('receipts.index')->with('success', 'Recibo eliminado exitosamente.');
    }
}
