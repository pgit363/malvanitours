<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BaseController as BaseController;

class PlaceController extends BaseController
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
        $places = Place::withCount(['photos', 'comments'])
            ->with('photos', 'city:id,name,image_url')
            ->paginate(10);
        return $this->sendResponse($places, 'Places successfully Retrieved...!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stops()
    {
        $places = Place::withCount(['photos', 'comments'])
            ->with(['photos', 'city:id,name,image_url', 'placeCategory:id,name,icon'])
            ->join('place_categories', 'places.place_category_id', '=', 'place_categories.id')
            ->whereIn('place_categories.name', ['Bus Stop', 'Bus Depo'])
            ->select('places.id', 'places.name', 'places.city_id', 'places.parent_id', 'places.place_category_id', 'places.image_url', 'places.bg_image_url', 'places.visitors_count')
            ->paginate(10);

        return $this->sendResponse($places, 'Stops successfully Retrieved...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //write visitors count update here by one
        $place = Place::withCount(['photos', 'comments'])
            ->with(['photos', 'city', 'comments', 'comments.comments', 'comments.users', 'comments.comments.users'])
            ->find($id);

        // $city = City::withCount(['projects', 'places', 'photos', 'comments'])
        //             ->with(['comments', 'comments.comments', 'comments.users', 'comments.comments.users', 'places', 'photos'])
        //             ->latest()
        //             ->limit(10)
        //             ->find($id);

        if (is_null($place)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($place, 'Place successfully Retrieved...!');
    }
}
