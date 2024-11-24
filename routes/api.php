<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('/registration', [AuthController::class, 'registration']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/authors', [AuthorController::class, 'index']);

    Route::middleware(['abilities:librarian'])->group(function () {
        Route::post('/authors', [AuthorController::class, 'store']);
        Route::patch("/authors/{author}", [AuthorController::class, 'update']);
        Route::delete("/authors/{author}", [AuthorController::class, 'destroy']);
    });
});
