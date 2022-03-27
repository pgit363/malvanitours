<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\API\V1\CategoriesController;
use App\Http\Controllers\API\V1\ProjectsController;
use App\Http\Controllers\API\V1\ProductsController;
use App\Http\Controllers\API\V1\RolesController;


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
    Route::post('/contact', [ContactController::class, 'store']);
    Route::get('/contact/{id}', [ContactController::class, 'index']);    
    Route::put('/contact/{id}', [ContactController::class, 'update']);
    Route::delete('/contact/{id}', [ContactController::class, 'destroy']);

    Route::get('/categories', [CategoriesController::class, 'index']);   
    Route::post('/categories', [CategoriesController::class, 'store']);
    Route::get('/categories/{id}', [CategoriesController::class, 'show']);
    Route::get('/categories/project/{categories_id}', [CategoriesController::class, 'getAllProjects']);   
    Route::put('/categories/{id}', [CategoriesController::class, 'update']);   
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']);   

    Route::get('/projects', [ProjectsController::class, 'index']);   
    Route::post('/project', [ProjectsController::class, 'store']);
    Route::get('/project/{id}', [ProjectsController::class, 'show']);   
    Route::put('/project/{id}', [ProjectsController::class, 'update']);   
    Route::delete('/project/{id}', [ProjectsController::class, 'destroy']);   
    Route::get('/project/{id}/products', [ProjectsController::class, 'getAllProducts']); 

    Route::get('/products', [ProductsController::class, 'index']);   
    Route::post('/product', [ProductsController::class, 'store']);
    Route::get('/product/{id}', [ProductsController::class, 'show']);   
    Route::put('/product/{id}', [ProductsController::class, 'update']);   
    Route::delete('/product/{id}', [ProductsController::class, 'destroy']);   

    Route::get('/roles', [RolesController::class, 'index']);   
    Route::post('/role', [RolesController::class, 'store']);
    Route::get('/role/{id}', [RolesController::class, 'show']);   
    Route::put('/role/{id}', [RolesController::class, 'update']);   
    Route::delete('/role/{id}', [RolesController::class, 'destroy']);  
    Route::get('/role/{id}/users', [RolesController::class, 'getAllUsers']); 

});