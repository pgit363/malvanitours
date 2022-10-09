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
        $categories = Category::withCount('projects')
                            ->latest()
                            ->limit(5)
                            ->get();

        $cities = City::withAvg("rateable", 'rate')
                        // ->having('rateable_avg_rate', '>', 3)
                        ->withCount('places','photos')
                        ->latest()
                        ->limit(5)
                        ->get();

        $projects = Projects::withAvg("rateable", 'rate')
                            // ->having('rateable_avg_rate', '>', 3) //this condiion is working
                            ->withCount('photos')
                            ->latest()
                            ->limit(5)
                            ->get();

        // $products = Products::withAvg("rateable", 'rate')
        //                     ->having('rateable_avg_rate', '>', 3)
        //                     ->withCount('comments','photos')
        //                     ->latest()
        //                     ->limit(6)
        //                     ->get();

        $places = Place::withAvg("rateable", 'rate')
                        // ->having('rateable_avg_rate', '>', 3)
                        ->orWhere('visitors_count', '>=', 5)
                        ->withCount('photos')
                        ->with(['placeCategory' => function ($query) {
                            $query->select('id', 'name', 'icon');
                        }, 'city' => function ($query) {
                            $query->select('id', 'name', 'image_url');
                        }])
                        ->latest()
                        ->limit(5)
                        ->get();

        $blogs = Blog::latest()
                       ->limit(5)
                       ->get();
                       
        $temp3 =  array_merge(['categories'=> $categories, 
                            'cities'=>$cities,
                            'projects'=>$projects,
                            // 'products'=>$products,
                            'places'=>$places,
                            'blogs'=>$blogs]);
    
        return $this->sendResponse($temp3, 'Landing page data successfully Retrieved...!');  
    }
}
