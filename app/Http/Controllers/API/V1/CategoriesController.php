<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Categories;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;

class CategoriesController extends BaseController
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
        $categories = Categories::paginate(10);
        return $this->sendResponse($categories, 'Categories successfully Retrieved...!');   
    }

      /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllProjects($id)
    {
        $projects = Categories::find($id)->projects;
        
        if (is_null($projects)) {
            return $this->sendError('Empty', [], 404);
        }

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
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }
      
        $categories = Categories::create($request->all());

        return $this->sendResponse($categories, 'Categories stored successfully...!');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $categories = Categories::find($id);
        
        if (is_null($categories)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($categories, 'Categories successfully Retrieved...!');   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit(Categories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $categories = Categories::find($id);

        if (is_null($categories)) {
            return $this->sendError('Empty', [], 404);
        }

        $categories->update($request->all());

        return $this->sendResponse($categories, 'Categories updated successfully...!');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $categories = Categories::find($id);

        if (is_null($categories)) {
            return $this->sendError('Empty', [], 404);
        }

        $categories->delete($request->all());

        return $this->sendResponse($categories, 'Categories deleted successfully...!');   
    }
}
