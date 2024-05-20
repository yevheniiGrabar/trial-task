<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users-by-country', [UserController::class, 'getUsersByCountry']);
Route::post('/upload-pdf', [FileController::class, 'uploadPdf']);
