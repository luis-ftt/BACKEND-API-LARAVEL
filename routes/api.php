<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth:sanctum')->post('/user/edit', [AuthController::class, 'NomeEdit'])->name('NomeEdit');



Route::get('/user', function(Request $request){
    return $request->user();
})->middleware('auth:sanctum');
