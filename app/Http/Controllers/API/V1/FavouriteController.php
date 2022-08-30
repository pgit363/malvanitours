<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Favourite;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;

class FavouriteController extends BaseController
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
        $favourite =  Favourite::paginate(10);

        return $this->sendResponse($favourite, 'Favourites successfully Retrieved...!');  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|numeric',
            'favouritable_type' => 'required|string',
            'favouritable_id' => 'required|numeric',
        ]);            

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $data = getData($request->favouritable_id, $request->favouritable_type);

        if (!$data) {
            return $this->sendError($request->favouritable_type.' Not Exist..!', '', 400);       
        }

        $favourite = new Favourite;
        
        $favourite->user_id = $request->get('user_id');
        
        $favourite->favouritable()->associate($data);

        $favourite = Favourite::create(json_decode($favourite, true));       

        return $this->sendResponse($favourite, 'Favourite created successfully...!');    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $favourites  = Favourite::with('favouritable')
                                // ->groupBy('favouritable_id')
                                ->orderBy('created_at', 'desc')
                                // ->latest()      
                                ->where('user_id', $user_id)                  
                                ->get();
        
        if (is_null($favourites)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($favourites, 'Favourites successfully Retrieved...!');  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function edit(Favourite $favourite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favourite $favourite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $favourite = Favourite::find($id);

        if (is_null($favourite)) {
            return $this->sendError('Empty', [], 404);
        }

        $favourite->delete($id);

        return $this->sendResponse($favourite, 'Favourite deleted successfully...!');   
    }
}
