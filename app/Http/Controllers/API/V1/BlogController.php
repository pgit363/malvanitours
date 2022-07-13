<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Blog;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BaseController as BaseController;

class BlogController extends BaseController
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
        $blogs = Blog::paginate(10);

        return $this->sendResponse($blogs, 'Blogs successfully Retrieved...!');
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
            'title' => 'required|string|between:2,200',
            'description' => 'required|string',           
            'ratings' => 'nullable|numeric',
            'count' => 'nullable|numeric',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $blog = Blog::create($request->all());

        return $this->sendResponse($blog, 'Blog added successfully...!');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::find($id);
        
        if (is_null($blog)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($blog, 'Blog successfully Retrieved...!');  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|between:2,100',
            'title' => 'string|between:2,200',
            'description' => 'string',           
            'ratings' => 'nullable|numeric',
            'count' => 'nullable|numeric',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $blog = Blog::find($id);

        if (is_null($blog)) {
            return $this->sendError('Empty', [], 404);
        }

        $blog->update($request->all());

        return $this->sendResponse($blog, 'Blogs updated successfully...!');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (is_null($blog)) {
            return $this->sendError('Empty', [], 404);
        }

        $blog->delete($request->all());

        return $this->sendResponse($blog, 'Blog deleted successfully...!');   
    }
}
