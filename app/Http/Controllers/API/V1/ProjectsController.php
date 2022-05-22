<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Projects;
use Illuminate\Http\Request;
use Validator;
use App\Models\Categories;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;


class ProjectsController extends BaseController
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Projects::paginate(10);
        return $this->sendResponse($projects, 'Projects successfully Retrieved...!');   
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
            'category_id' => 'required|numeric',
            'domain_name' => 'required|string',
            'logo' => 'required|mimes:jpeg,jpg,png|max:2048',
            'fevicon' => 'required|mimes:jpeg,jpg,png|max:2048',
            'description' => 'required|string',
            // 'ratings' => 'numeric',  add into users api
            'picture' => 'string',
            'start_price' => 'numeric',
            'speciality' => 'string',
            'link_status' => 'boolean',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }
      
        // Image 1 store      
        $image1 = $request->file('logo')->getClientOriginalName();

        $logo = $request->file('logo')->store('public/assets/projects/'.$request->name);

        $request->logo = Storage::url($logo);

        $image2 = $request->file('fevicon')->getClientOriginalName();

        $fevicon = $request->file('fevicon')->store('public/assets/projects/'.$request->name);

        $request->fevicon = Storage::url($fevicon);
 
        $projects = Projects::create($request->all());

        return $this->sendResponse($projects, 'Projects added successfully...!');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $projects = Projects::find($id);
        
        if (is_null($projects)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($projects, 'Projects successfully Retrieved...!');   
    }


    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllProducts($id)
    {
        $products = Projects::find($id)->products;
        
        if (is_null($products)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($products, 'Products successfully Retrieved...!'); 
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function edit(rojects $projects)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'category_id' => 'required|numeric',
            'domain_name' => 'required|string',
            'logo' => 'string',
            'fevicon' => 'string',
            'description' => 'required|string',
            'ratings' => 'numeric',
            'picture' => 'string',
            'start_price' => 'numeric',
            'speciality' => 'string',
            'link_status' => 'boolean',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $projects = Projects::find($id);

        if (is_null($projects)) {
            return $this->sendError('Empty', [], 404);
        }

        $projects->update($request->all());

        return $this->sendResponse($projects, 'Projects updated successfully...!');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $projects = Projects::find($id);

        if (is_null($projects)) {
            return $this->sendError('Empty', [], 404);
        }

        $projects->delete($request->all());

        return $this->sendResponse($projects, 'Projects deleted successfully...!');   
    }
}
