<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);  
    Route::post('/users', [AuthController::class, 'index']);    
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('/contact', [ContactController::class, 'index']);   
    Route::get('/contact/{id}', [ContactController::class, 'index']);    
    Route::post('/contact', [ContactController::class, 'store']);
    Route::put('/contact/{id}', [ContactController::class, 'update']);
    Route::delete('/contact/{id}', [ContactController::class, 'destroy']);
});