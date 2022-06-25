<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Photos;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PhotosController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photos::paginate(10);
        return $this->sendResponse($photos, 'Photos successfully Retrieved...!');  
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
            'project_id' => 'sometimes|numeric',
            'product_id' => 'sometimes|numeric',
            'place_id' => 'sometimes|numeric',
            'comment_id' => 'sometimes|numeric',            
            'url' => 'mimes:jpeg,jpg,png|max:2048',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }
      
        $input = $request->all();
        Log::info("upload file starting");

        //Image 1 store      
        if ($image = $request->file('url')) {
            Log::info("inside upload url");
            
            $url = date('YmdHis') . "." . $image->getClientOriginalExtension();

            $path = $request->file('url')->store(config('constants.upload_path.photo').$request->name);

            $input['url'] = Storage::url($path);
            
            Log::info("FILE STORED".$input['url']);
        }

        $photos = Photos::create($input);

        return $this->sendResponse($photos, 'Photos added successfully...!');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photos  $photos
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $photos = Photos::find($id);
        
        if (is_null($photos)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($photos, 'Photos successfully Retrieved...!');  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Photos  $photos
     * @return \Illuminate\Http\Response
     */
    public function edit(Photos $photos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photos  $photos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photos $photos)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required_without:product_id',
            'product_id' => 'required_without:project_id',
            'url' => 'string',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }
      
        $photos = Photos::create($request->all());

        if (is_null($photos)) {
            return $this->sendError('Empty', [], 404);
        }

        $photos->update($request->all());

        return $this->sendResponse($photos, 'Photos updated successfully...!');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photos  $photos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $photos = Photos::find($id);

        if (is_null($photos)) {
            return $this->sendError('Empty', [], 404);
        }

        $photos->delete($request->all());

        return $this->sendResponse($photos, 'Photos deleted successfully...!');   
    }
}
