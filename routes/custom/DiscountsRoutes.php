<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Discount\DiscountController;

Route::middleware(['auth'])->group(function () {
    Route::resource('discounts', DiscountController::class)->except(['show']);
});