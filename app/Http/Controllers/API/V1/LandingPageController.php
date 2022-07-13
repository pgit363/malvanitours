<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Categories;
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
        $categories = Categories::get();
        $projects = Projects::orderBy('id', 'DESC')->limit(10)->get();
        $places = Place::orderBy('id', 'DESC')->limit(10)->get();
        $cities = City::orderBy('id', 'DESC')->get();
        $blogs = Blog::orderBy('id', 'DESC')->limit(10)->get();
        $products = Products::where('ratings', '>=', 3)->orderBy('id', 'DESC')->limit(10)->get();

        $home =  array_merge(['categories'=> $categories, 
                              'projects'=> $projects, 
                              'places'=>$places, 
                              'products'=> $products,
                              'cities'=> $cities,
                              'blogs'=>$blogs]);
        
        return $this->sendResponse($home, 'Landing page data successfully Retrieved...!');  
    }
}
