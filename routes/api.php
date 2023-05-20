<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\ProjectsController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\RolesController;
use App\Http\Controllers\API\V1\PhotosController;
use App\Http\Controllers\API\V1\LandingPageController;
use App\Http\Controllers\API\V1\PlaceController;
use App\Http\Controllers\API\V1\{ ContactController, RatingController, RouteController};
use App\Http\Controllers\API\V1\BlogController;
use App\Http\Controllers\API\V1\HomeController;
use App\Http\Controllers\API\V1\CityController;
use App\Http\Controllers\API\V1\CommentController;
use App\Http\Controllers\API\V1\FavouriteController;
use App\Http\Controllers\API\V1\AddressController;
use App\Http\Controllers\API\V1\PlaceCategoryController;

use App\Http\Controllers\Admin\FoodController;


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
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/users', [AuthController::class, 'index']);    
    Route::post('/sendOtp', [AuthController::class, 'sendOtp']);
    Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
});

Route::group(['middleware' => 'api', 'prefix' => 'v1'], function ($router) {
    Route::get('roleDD', [RolesController::class, 'roleDD']);   
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function ($router) {

    Route::get('/user-profile', [AuthController::class, 'userProfile']);  
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/landingpage', [LandingPageController::class, 'index']);   
    Route::post('/search', [HomeController::class, 'search']);   

    Route::get('/cities', [CityController::class, 'index']);  
    Route::get('/city/{id}', [CityController::class, 'show']);    
    Route::get('/city/{id}/detail', [CityController::class, 'getAllcities']); 
    
    Route::get('/placecategories', [PlaceCategoryController::class, 'index']);   

    Route::get('/places', [PlaceController::class, 'index']);  
    Route::get('/place/{id}', [PlaceController::class, 'show']);    
    
    Route::get('/stops', [PlaceController::class, 'stops']);  
    Route::post('/searchPlace', [PlaceController::class, 'searchPlace']);  

    Route::get('/listroutes', [RouteController::class, 'listroutes']);  
    Route::post('/routes', [RouteController::class, 'routes']);  

    Route::get('/contacts', [ContactController::class, 'index']);  
    Route::post('/contact', [ContactController::class, 'store']);
    Route::get('/contact/{id}', [ContactController::class, 'show']);    
    Route::put('/contact/{id}', [ContactController::class, 'update']);
    Route::delete('/contact/{id}', [ContactController::class, 'destroy']);

    Route::post('/address', [AddressController::class, 'store']);
    Route::put('/address/{id}', [AddressController::class, 'update']);
    Route::delete('/address/{id}', [AddressController::class, 'destroy']);

    Route::get('/blogs', [BlogController::class, 'index']);  
    Route::post('/blog', [BlogController::class, 'store']);
    Route::get('/blog/{id}', [BlogController::class, 'show']);    
    Route::get('/blog/category/{id}', [BlogController::class, 'blogByCategory']);    
    Route::put('/blog/{id}', [BlogController::class, 'update']);
    Route::delete('/blog/{id}', [BlogController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);   
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::get('/category/{categories_id}/project', [CategoryController::class, 'getAllProjects']);   
    Route::get('/category/{id}/productcategories', [CategoryController::class, 'getAllowedProductCategories']);
    Route::post('/category/{id}', [CategoryController::class, 'update']);   
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);   

    Route::post('/projects', [ProjectsController::class, 'index']);   
    Route::post('/project', [ProjectsController::class, 'store']);
    Route::get('/project/{id}', [ProjectsController::class, 'show']);   
    Route::post('/project/{id}', [ProjectsController::class, 'update']);   
    Route::delete('/project/{id}', [ProjectsController::class, 'destroy']);   
    // Route::get('/project/{id}/products', [ProjectsController::class, 'getAllProducts']); 

    // Route::get('/products', [ProductsController::class, 'index']);   
    Route::post('/project/{project_id}/products', [ProductController::class, 'getAllProductsByProjectId']);   
    // Route::post('/product', [ProductsController::class, 'store']);
    // Route::post('/product/{id}', [ProductsController::class, 'update']);  
    // Route::delete('/product/{id}', [ProductsController::class, 'destroy']);   


    Route::get('/food/{id}', [FoodController::class, 'show']);    


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

    Route::get('/comments', [CommentController::class, 'index']);   
    Route::post('/comment', [CommentController::class, 'store']);
    Route::get('/comment/{id}', [CommentController::class, 'show']);   
    Route::put('/comment/{id}', [CommentController::class, 'update']);   
    Route::delete('/comment/{id}', [CommentController::class, 'destroy']); 
    
    Route::get('/favourites', [FavouriteController::class, 'index']);   
    Route::post('/favourite', [FavouriteController::class, 'store']);
    Route::get('/favourite/{user_id}', [AuthController::class, 'getAllFavourites']);   
    // Route::put('/favourite/{id}', [FavouriteController::class, 'update']);   
    Route::delete('/favourite/{id}', [FavouriteController::class, 'destroy']); 

    Route::get('/ratings', [RatingController::class, 'index']);  
    Route::post('/rating', [RatingController::class, 'store']);
    Route::put('/rating/{id}', [RatingController::class, 'update']);
    Route::delete('/rating/{id}', [RatingController::class, 'destroy']);
});