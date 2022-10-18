<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\PlaceCategoryController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\AllowedProductCategoryController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AccomodationCategoryController;

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
Route::get('/', function() {
    print('I am an admin');
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);  
    Route::post('/users', [AuthController::class, 'index']);    
});

Route::group(['middleware' => 'api', 'prefix' => 'api'], function ($router) {
    Route::get('/cities', [CityController::class, 'index']);  
    Route::post('/city', [CityController::class, 'store']);
    Route::get('/city/{id}', [CityController::class, 'show']);    
    Route::post('/city/{id}', [CityController::class, 'update']);
    Route::delete('/city/{id}', [CityController::class, 'destroy']);
    Route::get('/city/{id}/detail', [CityController::class, 'getAllcities']); 

    Route::get('/placecategories', [PlaceCategoryController::class, 'index']);   
    Route::post('/placecategory', [PlaceCategoryController::class, 'store']);
    Route::get('/placecategory/{id}', [PlaceCategoryController::class, 'show']);
    Route::get('/placecategory/places/{place_categories_id}', [PlaceCategoryController::class, 'getAllPlaces']);   
    Route::post('/placecategory/{id}', [PlaceCategoryController::class, 'update']);   
    Route::delete('/placecategory/{id}', [PlaceCategoryController::class, 'destroy']);   

    Route::get('/places', [PlaceController::class, 'index']);  
    Route::post('/place', [PlaceController::class, 'store']);
    Route::get('/place/{id}', [PlaceController::class, 'show']);    
    Route::post('/place/{id}', [PlaceController::class, 'update']);
    Route::delete('/place/{id}', [PlaceController::class, 'destroy']);

    Route::get('/productcategories', [ProductCategoryController::class, 'index']);   
    Route::post('/productcategory', [ProductCategoryController::class, 'store']);
    Route::get('/productcategory/{id}', [ProductCategoryController::class, 'show']);
    Route::post('/productcategory/{id}', [ProductCategoryController::class, 'update']);   
    Route::delete('/productcategory/{id}', [ProductCategoryController::class, 'destroy']);  

    Route::post('/allowproductcategory', [AllowedProductCategoryController::class, 'store']);
    Route::post('/allowproductcategory/{id}', [AllowedProductCategoryController::class, 'update']);   
    Route::delete('/allowproductcategory/{id}', [AllowedProductCategoryController::class, 'destroy']); 

    Route::get('/products', [ProductController::class, 'index']);  
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product/{id}', [ProductController::class, 'show']);    
    Route::post('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    Route::get('/foods', [FoodController::class, 'index']);  
    Route::post('/food', [FoodController::class, 'store']);
    Route::get('/food/{id}', [FoodController::class, 'show']);    
    Route::post('/food/{id}', [FoodController::class, 'update']);
    Route::delete('/food/{id}', [FoodController::class, 'destroy']);

    Route::get('/tourpackages', [TourPackageController::class, 'index']);  
    Route::post('/tourpackage', [TourPackageController::class, 'store']);
    Route::get('/tourpackage/{id}', [TourPackageController::class, 'show']);    
    Route::post('/tourpackage/{id}', [TourPackageController::class, 'update']);
    Route::delete('/tourpackage/{id}', [TourPackageController::class, 'destroy']);

    Route::get('/accomodationcategories', [AccomodationCategoryController::class, 'index']);  
    Route::post('/accomodationcategory', [AccomodationCategoryController::class, 'store']);
    Route::get('/accomodationcategory/{id}', [AccomodationCategoryController::class, 'show']);    
    Route::post('/accomodationcategory/{id}', [AccomodationCategoryController::class, 'update']);
    Route::delete('/accomodationcategory/{id}', [AccomodationCategoryController::class, 'destroy']);
}); 
