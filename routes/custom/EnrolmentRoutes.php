<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\EnrolmentController;

Route::middleware(['auth'])->group(function () {
    Route::resource('enrolments', EnrolmentController::class)->except(['show']);


});