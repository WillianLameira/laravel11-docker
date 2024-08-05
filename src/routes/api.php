<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;


Route::get('/login', function () {
    return response()->json(['message' => 'Para acessar essa rota é necessário estar autenticado'], 401);
})->name('login');

Route::group(['prefix' => 'v1'], function () {

    Route::post('login', [AuthController::class, 'login']);


    Route::middleware('auth:api')->group(function () {

        Route::middleware('scopes:register-user')->group(function () {
            Route::post('register', [AuthController::class, 'register'])->name('register.api');
        });

        Route::get('get-user', [AuthController::class, 'userInfo']);

    });

});
