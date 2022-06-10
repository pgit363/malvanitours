<?php

use App\Http\Controllers\API\V1\Admin\CityController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);  
    Route::post('/users', [AuthController::class, 'index']);    
});

Route::get('/', function() {
    print('I am an admin');
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('/cities', [CityController::class, 'index']);  
    Route::post('/city', [CityController::class, 'store']);
    Route::get('/city/{id}', [CityController::class, 'index']);    
    Route::put('/city/{id}', [CityController::class, 'update']);
    Route::delete('/city/{id}', [CityController::class, 'destroy']);
});
