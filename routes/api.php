<?php

use App\Http\Controllers\PersonsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;

Route::apiResource('persons', PersonsController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rotte per Admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}/role', [UserController::class, 'updateRole']);
    Route::get('/users', [UserController::class, 'index']);
});

// Rotte per Editor
Route::middleware(['auth:sanctum', 'role:admin,editor'])->group(function () {
    Route::post('/persons', [PersonsController::class, 'store']);
    Route::get('/persons/{id}', [PersonsController::class, 'show']);
    Route::put('/persons/{id}', [PersonsController::class, 'update']);
    Route::delete('/persons/{id}', [PersonsController::class, 'destroy']);
});

// Rotte per Viewer e superiori
Route::middleware(['auth:sanctum', 'role:admin,editor,viewer'])->group(function () {
    Route::get('/persons', [PersonsController::class, 'index']);
    Route::get('/persons/{id}', [PersonsController::class, 'show']);
});
// Aggiunte 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index']);

// Test route
//Route::get('/test-users', function () {
//    return response()->json(User::all());
//});

// Error login route
Route::get('/login', function() {
    return response()->json(['error' => 'Login required'], 401);
})->name('login');

// Fallback route
Route::fallback(function(){
    return response()->json(['message' => 'Resource not found.'], 404);
});
