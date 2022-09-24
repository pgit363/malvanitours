<?php

namespace App\Http\Controllers\API\V1;

use App\Models\PlaceCategory;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController as BaseController;

class PlaceCategoryController extends Controller
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
        $placeCategory = PlaceCategory::with(['places'])
                                        ->paginate(10);
        return $this->sendResponse($placeCategory, 'Place Category successfully Retrieved...!');  
    }
}
