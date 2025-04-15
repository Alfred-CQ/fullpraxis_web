<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\StudentController;

Route::middleware(['auth'])->group(function () {


    Route::resource('students', StudentController::class)->except(['show']);

    Route::get('students/registrar', [StudentController::class, 'create'])->name('students.enroll');
    Route::post('students/registrar', [StudentController::class, 'store'])->name('students.enroll.store');
    Route::delete('students/registrar/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/students/{id}/carnet', [StudentController::class, 'generateCarnetPdf'])->name('students.carnet');
    Route::get('/students/carnets', [StudentController::class, 'generateAllCarnetsPdf'])->name('students.carnets');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::post('/students/{id}', [StudentController::class, 'update'])->name('students.update');


    Route::get('/students/{id}/attendance-report-pdf', [StudentController::class, 'attendanceReportPdf'])->name('students.attendance-report-pdf');
    Route::get('/students/{id}/attendance-report', [StudentController::class, 'attendanceReportPdf'])->name('students.attendance-report');

    //Route::get('/students/{id}/calendar', [StudentController::class, 'calendar'])->name('students.calendar');

    Route::post('/students/actions/download-selected', [StudentController::class, 'downloadSelected'])->name('students.nada.download-selected');
});
