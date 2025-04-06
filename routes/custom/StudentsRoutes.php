<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\StudentController;

Route::middleware(['auth'])->group(function () {
    Route::resource('students', StudentController::class)->except(['show']);

    Route::get('students/registrar', [StudentController::class, 'create'])->name('students.enroll');
    Route::post('students/registrar', [StudentController::class, 'store'])->name('students.enroll.store');
    Route::delete('students/registrar/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});
