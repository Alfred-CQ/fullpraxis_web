<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Receipts\ReceiptController;

Route::middleware(['auth'])->group(function () {


    Route::resource('receipts', ReceiptController::class)->except(['show']);

});
