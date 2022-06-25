<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Models\City;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
      
        $input = $request->all();
        $destinationPath = 'public/assets/cities/'; 

        // Image 1 store      
        if ($image = $request->file('image_url')) {
            Log::info("inside upload image_url");
            
            $image_url = $request->name.date('YmdHis'). "." . $image->getClientOriginalExtension();

            $path = $request->file('image_url')->store(config('constants.upload_path.city').$request->name);

            $input['image_url'] = Storage::url($path);
            
            Log::info("FILE STORED".$input['image_url']);
        }

        // Image 2 store      
        if ($image = $request->file('bg_image_url')) {
            Log::info("inside upload bg_image_url");
            
            $bg_image_url = $request->name."." . $image->getClientOriginalExtension();

            $path = $request->file('bg_image_url')->store($destinationPath.$request->name);

            $input['bg_image_url'] = Storage::url($path);
            
            Log::info("FILE STORED".$input['bg_image_url']);
        }

        //inserting into table
        $city = City::create($input);

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
