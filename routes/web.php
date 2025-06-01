<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

Route::get('/', function () {
    return Inertia::render('auth/login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::get('/test-image', function () {
    $manager = new ImageManager(new Driver());

    $image = $manager->read(public_path('carnet_fullpraxis.png'))
    ->text('Nombre del Alumno', 280, 160, function ($font) {
        $font->filename(public_path('fonts/Open_Sans/static/OpenSans-Bold.ttf'));
        $font->size(32);
        $font->color('#000000');
        $font->align('left');
        $font->valign('top');
    });

    return response($image->toJpeg())->header('Content-Type', 'image/jpeg');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

require __DIR__ . '/custom/StudentsRoutes.php';
require __DIR__ . '/custom/AcademicTermRoutes.php';
require __DIR__ . '/custom/DiscountsRoutes.php';
require __DIR__ . '/custom/EnrollmentRoutes.php';
require __DIR__ . '/custom/ReceiptsRoutes.php';
