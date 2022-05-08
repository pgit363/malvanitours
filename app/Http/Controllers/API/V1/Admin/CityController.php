<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Models\City;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;

class CityController extends BaseController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::paginate(10);
        return $this->sendResponse($cities, 'Cities successfully Retrieved...!');  
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
            'name' => 'required_without:product_id',
            'tag_line' => 'required_without:project_id',
            'famous_for' => 'required',
            'image_url' => 'required|mimes:jpeg,jpg,png|max:2048',
            'bg_image_url' => 'required|mimes:jpeg,jpg,png|max:2048',
            'url' => 'string',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }
      
        // Image 1 store      
        $image1 = $request->file('image_url')->getClientOriginalName();
 
        $image_path1 = $request->file('image_url')->store('public/assets/city/'.$request->name);
 
        $request->image_url = Storage::url($image_path1);

        // Image 2 store
        $image2 = $request->file('bg_image_url')->getClientOriginalName();
 
        $image_path2 = $request->file('bg_image_url')->store('public/assets/city/'.$request->name);

        $request->bg_image_url = Storage::url($image_path2);

        //inserting into table
        $city = City::create($request->all());

        return $this->sendResponse($city, 'City added successfully...!');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
    }
}
