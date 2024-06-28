<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuotesController;
use Illuminate\Support\Facades\Route;

Route::post('/token', [AuthController::class, 'token']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/quotes', [QuotesController::class, 'index']);
});