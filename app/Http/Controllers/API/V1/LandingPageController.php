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
use App\Models\PlaceCategory;

class LandingPageController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #Services categories
        $categories = Category::withCount('projects')
            ->latest()
            ->limit(5)
            ->get();
        #Top famouse cities
        $cities = City::withAvg("rateable", 'rate')
            // ->having('rateable_avg_rate', '>', 3)
            ->withCount('places', 'photos')
            ->latest()
            ->limit(5)
            ->get();
        # Top Projects
        $projects = Projects::withAvg("rateable", 'rate')
            // ->having('rateable_avg_rate', '>', 3) //this condition is working
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

        #Bus Stops / Depos
        $stops = Place::withAvg("rateable", 'rate')
            ->select('id', 'name', 'city_id', 'parent_id', 'place_category_id', 'image_url', 'bg_image_url', 'visitors_count')
            ->orWhere('visitors_count', '>=', 5)
            ->whereIn('place_category_id', [3, 4])
            ->latest()
            ->limit(5)
            ->get();

        #Place Categories
        $place_catgory = PlaceCategory::withCount('places')
            ->latest()
            ->limit(5)
            ->get();

        #Top Places
        //add location based filter near by famous locations
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

        $records =  array(
            'categories' => $categories,
            'cities' => $cities,
            'projects' => $projects,
            // 'products'=>$products,
            'stops' => $stops,
            'place_category' => $place_catgory,
            'places' => $places,
            'blogs' => $blogs
        );

        return $this->sendResponse($records, 'Landing page data successfully Retrieved...!');
    }
}
