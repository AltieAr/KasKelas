<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClassAssetController;
use App\Http\Controllers\Api\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/assets', [ClassAssetController::class, 'index']);
Route::post('/assets', [ClassAssetController::class,'store']);
Route::get('/assets/{id}', [ClassAssetController::class, 'show']);
Route::put('/assets/{id}', [ClassAssetController::class, 'update']);
Route::delete('/assets/{id}', [ClassAssetController::class, 'destroy']);

Route::get('/students', [StudentController::class, 'index']);

