<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\ProjectsController;
use App\Http\Controllers\API\V1\ProductsController;
use App\Http\Controllers\API\V1\RolesController;
use App\Http\Controllers\API\V1\PhotosController;
use App\Http\Controllers\API\V1\LandingPageController;
use App\Http\Controllers\API\V1\PlaceController;
use App\Http\Controllers\API\V1\BlogController;
use App\Http\Controllers\API\V1\ContactController;

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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);  
    Route::post('/users', [AuthController::class, 'index']);    
    Route::post('/sendOtp', [AuthController::class, 'sendOtp']);
    Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
});


Route::group(['middleware' => 'api', 'prefix' => 'v1'], function ($router) {

    Route::get('/landingpage', [LandingPageController::class, 'index']);   

    Route::get('/contacts', [ContactController::class, 'index']);  
    Route::post('/contact', [ContactController::class, 'store']);
    Route::get('/contact/{id}', [ContactController::class, 'show']);    
    Route::put('/contact/{id}', [ContactController::class, 'update']);
    Route::delete('/contact/{id}', [ContactController::class, 'destroy']);

    Route::get('/blogs', [BlogController::class, 'index']);  
    Route::post('/blog', [BlogController::class, 'store']);
    Route::get('/blog/{id}', [BlogController::class, 'show']);    
    Route::put('/blog/{id}', [BlogController::class, 'update']);
    Route::delete('/blog/{id}', [BlogController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);   
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::get('/category/project/{categories_id}', [CategoryController::class, 'getAllProjects']);   
    Route::post('/category/{id}', [CategoryController::class, 'update']);   
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);   

    Route::get('/projects', [ProjectsController::class, 'index']);   
    Route::post('/project', [ProjectsController::class, 'store']);
    Route::get('/project/{id}', [ProjectsController::class, 'show']);   
    Route::post('/project/{id}', [ProjectsController::class, 'update']);   
    Route::delete('/project/{id}', [ProjectsController::class, 'destroy']);   
    Route::get('/project/{id}/products', [ProjectsController::class, 'getAllProducts']); 

    Route::get('/products', [ProductsController::class, 'index']);   
    Route::post('/product', [ProductsController::class, 'store']);
    Route::get('/product/{id}', [ProductsController::class, 'show']);   
    Route::post('/product/{id}', [ProductsController::class, 'update']);  
    Route::delete('/product/{id}', [ProductsController::class, 'destroy']);   

    Route::get('/photos', [PhotosController::class, 'index']);   
    Route::post('/photo', [PhotosController::class, 'store']);
    Route::get('/photo/{id}', [PhotosController::class, 'show']);   
    Route::post('/photo/{id}', [PhotosController::class, 'update']);   
    Route::delete('/photo/{id}', [PhotosController::class, 'destroy']);   

    Route::get('/roles', [RolesController::class, 'index']);   
    Route::post('/role', [RolesController::class, 'store']);
    Route::get('/role/{id}', [RolesController::class, 'show']);   
    Route::put('/role/{id}', [RolesController::class, 'update']);   
    Route::delete('/role/{id}', [RolesController::class, 'destroy']);  
    Route::get('/role/{id}/users', [RolesController::class, 'getAllUsers']); 

});