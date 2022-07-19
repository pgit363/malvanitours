<?php

namespace App\Http\Controllers\API\V1;

use App\Models\City;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CityController extends BaseController
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
        $cities = City::withCount(['projects', 'places', 'photos', 'comments'])
                        ->latest()                
                        ->paginate(10);

        return $this->sendResponse($cities, 'Cities successfully Retrieved...!');  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city = City::withCount(['projects', 'places', 'photos', 'comments'])
                      ->with(['comments', 'comments.users'])
                      ->latest()
                      ->limit(10)
                      ->find($id);
    
                    // withCount(['projects', 'places', 'photos', 'comments'])
                    // // ->with(['projects', 'places', 'photos', 'comments'])
                    // ->whereId($id)
                    // ->latest()
                    // ->paginate(10);

        return $this->sendResponse($city, 'Cities successfully Retrieved...!');  
    }

     /**
     * Display a listing of the city.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllcities($id)
    {
        $cities = City::withCount(['projects', 'places', 'photos', 'comments'])
                        ->with(['projects', 'places', 'photos', 'comments'])
                        ->whereId($id)
                        ->latest()
                        ->paginate(10);
        
        if (is_null($cities)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($cities, 'Cities successfully Retrieved...!'); 
    }
}
