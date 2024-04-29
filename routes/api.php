<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TesteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\InvoiceController;


Route::prefix('v1')->group(function(){

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('ability:user-get');
    Route::get('/teste', [TesteController::class, 'index'])->middleware('ability:teste-index');
    Route::post('/logout', [AuthController::class, 'logout']);

    });

    Route::apiResource('invoices', InvoiceController::class);

});

