<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\EnrollmentController;

Route::middleware(['auth'])->group(function () {
    Route::resource('enrollments', EnrollmentController::class)->except(['show']);
});