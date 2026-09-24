<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassAssetController;
use App\Http\Controllers\Api\KasPeriodController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login-admin', [AuthController::class, 'login']);

Route::get('/assets', [ClassAssetController::class, 'index'])->middleware('auth:sanctum');
Route::post('/assets', [ClassAssetController::class,'store']);
Route::get('/assets/{id}', [ClassAssetController::class, 'show']);
Route::put('/assets/{id}', [ClassAssetController::class, 'update']);
Route::delete('/assets/{id}', [ClassAssetController::class, 'destroy']);

Route::get('/students', [StudentController::class, 'index'])->middleware('auth:sanctum');
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students/{id}/kas', [StudentController::class, 'rekapKas']);

Route::get('/transaction',[TransactionController::class,'index']);
Route::post('/transaction',[TransactionController::class, 'store']);

Route::get('/kas-period', [KasPeriodController::class, 'index']);
Route::post('/kas-period', [KasPeriodController::class, 'store']);
Route::get('/periods/{id}/status', [KasPeriodController::class, 'statusPembayaran']);
Route::put('/periods/update/{id}', [KasPeriodController::class, 'update']);





