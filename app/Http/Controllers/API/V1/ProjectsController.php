<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Projects;
use Illuminate\Http\Request;
use Validator;
use App\Models\Categories;
use App\Http\Controllers\BaseController as BaseController;


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
