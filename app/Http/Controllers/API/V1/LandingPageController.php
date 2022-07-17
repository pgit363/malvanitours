<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Category;
use App\Models\Projects;
use App\Models\Products;
use App\Models\Place;
use App\Models\City;
use App\Models\Blog;


class LandingPageController extends BaseController
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::withCount('projects', 'products')
                        // ->with(['projects.city'])
                        ->latest()
                        ->limit(10)
                        ->get();

        $cities = City::withCount('places','photos')
                        ->latest()
                        ->limit(10)
                        ->get();

        $projects = Projects::where('ratings', '>=', 3)
                              ->withCount('products','photos')
                              ->latest()
                              ->limit(10)
                              ->get();

        $places = Place::where('rating', '>=', 3)
                         ->orWhere('visitors_count', '>=', 10)
                         ->withCount('photos')
                         ->latest()
                         ->limit(10)
                         ->get();

        $blogs = Blog::latest()
                       ->limit(10)
                       ->get();
                       
        $temp3 =  array_merge(['categories'=> $categories, 
                            'cities'=>$cities,
                            'projects'=>$projects,
                            'places'=>$places,
                            'blogs'=>$blogs]);
    
        return $this->sendResponse($temp3, 'Landing page data successfully Retrieved...!');  
    }
}
