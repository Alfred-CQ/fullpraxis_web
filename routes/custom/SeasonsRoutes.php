<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Season\SeasonController;

Route::middleware(['auth'])->group(function () {
    Route::resource('seasons', SeasonController::class)->except(['show']);

    // Route::get('seasons/registrar', [StudentController::class, 'create'])->name('students.enroll');
    // Route::post('seasons/registrar', [StudentController::class, 'store'])->name('students.enroll.store');
    // Route::delete('students/registrar/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});
