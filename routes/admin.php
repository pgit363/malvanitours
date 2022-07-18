<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\PlaceController;
use App\Http\Controllers\API\V1\Admin\CityController;

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

Route::group(['middleware' => 'api', 'prefix' => 'api'], function ($router) {
    Route::get('/cities', [CityController::class, 'index']);  
    Route::post('/city', [CityController::class, 'store']);
    Route::get('/city/{id}', [CityController::class, 'show']);    
    Route::post('/city/{id}', [CityController::class, 'update']);
    Route::delete('/city/{id}', [CityController::class, 'destroy']);
    Route::get('/city/{id}/detail', [CityController::class, 'getAllcities']); 

    Route::get('/places', [PlaceController::class, 'index']);  
    Route::post('/place', [PlaceController::class, 'store']);
    Route::get('/place/{id}', [PlaceController::class, 'show']);    
    Route::post('/place/{id}', [PlaceController::class, 'update']);
    Route::delete('/place/{id}', [PlaceController::class, 'destroy']);
}); 
