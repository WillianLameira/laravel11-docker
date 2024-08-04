<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->get('/users', function (Request $request) {
    // Esta rota só poderá ser acessada se o token tiver o escopo 'view-users'
    if ($request->user()->tokenCan('view-users')) {
        return response()->json([
            'users' => [
                ['id' => 1, 'name' => 'User 1'],
                ['id' => 2, 'name' => 'User 2'],
            ]
        ]);
    }

    return response()->json(['message' => 'Unauthorized'], 403);
});
