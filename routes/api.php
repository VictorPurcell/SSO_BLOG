<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\DuoMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:api', DuoMiddleware::class])->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Acesso permitido!']);
    });
});
