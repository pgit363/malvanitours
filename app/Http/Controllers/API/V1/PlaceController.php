<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Http\Controllers\BaseController as BaseController;

class PlaceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $places = Place::paginate(10);
        return $this->sendResponse($places, 'Places successfully Retrieved...!');
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
            'name' => 'required|string|between:2,100',
            'city_id' => 'required|numeric',
            'description' => 'required|string',
            'rules' => 'json',
            'image_url' => 'required|mimes:jpeg,jpg,png|max:2048',
            'bg_image_url' => 'required|mimes:jpeg,jpg,png|max:2048',
            'price' => 'json',
            'rating' => 'numeric',
            'visitors_count' => 'numeric',
            'social_media' => 'json',
            'contact_details' => 'json',
            'categories' => 'string',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }
      
        // Image 1 store      
        $image1 = $request->file('image_url')->getClientOriginalName();

        $image_url = $request->file('image_url')->store('public/assets/places/'.$request->name);

        $request->image_url = Storage::url($image_url);

        $image2 = $request->file('bg_image_url')->getClientOriginalName();

        $bg_image_url = $request->file('bg_image_url')->store('public/assets/places/'.$request->name);

        $request->bg_image_url = Storage::url($bg_image_url);
 
        $place = Place::create($request->all());

        return $this->sendResponse($place, 'Place added successfully...!');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        //
    }
}
