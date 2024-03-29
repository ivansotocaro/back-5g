<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\UserController;
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


Route::post('login', [AuthController::class, 'login']);


Route::middleware('jwt.verify')->group(function(){
    Route::post('register', [UserController::class, 'store']);
    Route::get('user', [UserController::class, 'getUser']);
});
Route::post('savepayment', [PaymentController::class, 'create']);
Route::get('payment', [PaymentController::class, 'index']);
