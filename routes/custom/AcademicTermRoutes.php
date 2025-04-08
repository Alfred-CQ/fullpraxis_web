<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Academic\AcademicTermController;

Route::middleware(['auth'])->group(function () {
    Route::resource('AcademicTerm', AcademicTermController::class)->except(['show']);

    // Route::get('seasons/registrar', [StudentController::class, 'create'])->name('students.enroll');
    // Route::post('seasons/registrar', [StudentController::class, 'store'])->name('students.enroll.store');
    // Route::delete('students/registrar/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});
