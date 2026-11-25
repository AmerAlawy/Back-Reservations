<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShalehController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/shaleh', [ShalehController::class, 'createShaleh']);
    Route::get('/shalehs', [ShalehController::class, 'getAllShalehs']);
    Route::get('/my-shalehs', [ShalehController::class, 'myShalehs']);
    Route::get('/shalehs/{id}', [ShalehController::class, 'show']);
    Route::middleware('admin')->prefix('admin')->group(function () {

        // عرض الشاليهات التي تنتظر موافقة المدير
        Route::get('/shalehs/pending', [ShalehController::class, 'pendingShalehs']);

        // الموافقة على شاليه
        Route::patch('/shalehs/{id}/approve', [ShalehController::class, 'approveShaleh']);

        // رفض شاليه
        Route::patch('/shalehs/{id}/reject', [ShalehController::class, 'rejectShaleh']);

       });

});