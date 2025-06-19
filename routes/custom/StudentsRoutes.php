<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Attendances\AttendancesController;

Route::middleware(['auth'])->group(function () {


    Route::resource('students', StudentController::class)->except(['show']);

    Route::get('students/registrar', [StudentController::class, 'create'])->name('students.enroll');
    Route::post('students/registrar', [StudentController::class, 'store'])->name('students.enroll.store');
    // Route::delete('students/registrar/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/students/{id}/carnet', [StudentController::class, 'generateCarnetPdf'])->name('students.carnet');
    Route::get('/students/selected/carnets', [StudentController::class, 'generateSelectedCarnetsPdf'])->name('students.selected.carnets');
    //Route::get('/students/all/carnets', [StudentController::class, 'generateAllCarnetsPdf'])->name('students.all.carnets');
    // routes/web.php
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    // Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    // Route::post('/students/{id}', [StudentController::class, 'update'])->name('students.update');


    Route::get('/students/{id}/attendance-report-pdf', [StudentController::class, 'attendanceReportPdf'])->name('students.attendance-report-pdf');
    Route::get('/attendances/daily-report-pdf', [AttendancesController::class, 'dailyAttendanceReportPdf'])->name('attendances.daily-report-pdf');
    Route::get('/students/{id}/attendance-report', [StudentController::class, 'attendanceReportPdf'])->name('students.attendance-report');

    Route::get('/students/{id}/calendar', [StudentController::class, 'calendar'])->name('students.calendar');

    Route::post('/students/actions/download-selected', [StudentController::class, 'downloadSelected'])->name('students.nada.download-selected');
});
