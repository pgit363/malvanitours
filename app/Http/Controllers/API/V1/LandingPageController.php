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
use App\Models\Route;

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
            ->limit(8)
            ->get();
        #Top famouse cities
        $cities = City::select('id', 'name', 'tag_line', 'image_url')
            ->withAvg("rateable", 'rate')
            // ->having('rateable_avg_rate', '>', 3)
            ->withCount('places', 'photos')
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

        $routes = Route::with([
            'routeStops:id,serial_no,route_id,place_id,arr_time,dept_time,total_time,delayed_time',
            'routeStops.place:id,name,place_category_id',
            'routeStops.place.placeCategory:id,name,icon',
            'sourcePlace:id,name,place_category_id',
            'sourcePlace.placeCategory:id,name,icon',
            'destinationPlace:id,name,place_category_id',
            'destinationPlace.placeCategory:id,name,icon',
            'busType:id,type,logo'
        ])->select('id', 'source_place_id', 'destination_place_id', 'bus_type_id', 'name', 'start_time', 'end_time', 'total_time', 'delayed_time')
            ->latest()
            ->limit(5)
            ->get();

        #Place Categories
        $place_category = PlaceCategory::with(['places' => function ($query) {
            $query->select('places.id', 'places.name', 'places.city_id', 'places.parent_id', 'places.place_category_id', 'places.image_url', 'places.bg_image_url', 'places.visitors_count')
                ->leftJoin('places as p2', function ($join) {
                    $join->on('places.place_category_id', '=', 'p2.place_category_id')
                        ->whereRaw('places.id <= p2.id');
                })
                ->groupBy('places.id')
                ->havingRaw('COUNT(*) <= 5');
        }])
            ->withCount('places')
            ->limit(5)
            ->get();

        // return $place_catgory;
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
            'routes' => $routes,
            'stops' => $stops,
            'categories' => $categories,
            'cities' => $cities,
            'projects' => $projects,
            // 'products'=>$products,
            'place_category' => $place_category,
            'places' => $places,
            'blogs' => $blogs
        );

        return $this->sendResponse($records, 'Landing page data successfully Retrieved...!');
    }
}
