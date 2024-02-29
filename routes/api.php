<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('get/popular/name', [\App\Http\Controllers\UserController::class, 'getName']);
Route::post('get/count/popular/name', [\App\Http\Controllers\UserController::class, 'getCountName']);
Route::post('get/average/age', [\App\Http\Controllers\UserController::class, 'averageValue']);
Route::post('get/range/users', [\App\Http\Controllers\UserController::class, 'ageRange']);
Route::post('get/all/users', [\App\Http\Controllers\UserController::class, 'getAllItem']);
Route::post('update/user/data', [\App\Http\Controllers\UserController::class, 'changeUser']);
Route::post('new/user/data', [\App\Http\Controllers\UserController::class, 'saveUser']);
