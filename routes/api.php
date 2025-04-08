<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendances\AttendancesController;
use App\Http\Controllers\Students\StudentController;

use App\Models\User;
use Illuminate\Support\Facades\Hash;


Route::post('/register_attendance', [AttendancesController::class, 'attendanceRegisterApi']);

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Usuario no autenticado'], 401);
    }

    $token = $user->createToken('flutter-app')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Inicio de sesión exitoso',
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'nombre' => $user->name,
            'email' => $user->email,
        ]
    ]);
});


Route::post('/register', function (Request $request) {
    // Validar datos
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]);

    // Crear usuario
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
    ]);

    // Crear token de acceso
    $token = $user->createToken('flutter-app')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Registro exitoso',
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'nombre' => $user->name,
            'email' => $user->email,
        ]
    ], 201);
});