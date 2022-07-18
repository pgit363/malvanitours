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
        $places = Place::withCount(['photos'])
                        ->with('photos','city')
                        ->paginate(10);
        return $this->sendResponse($places, 'Places successfully Retrieved...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $place = Place::find($id);
        
        if (is_null($place)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($place, 'Place successfully Retrieved...!');  
    }
}
