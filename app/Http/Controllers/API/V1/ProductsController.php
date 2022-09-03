<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Products;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductsController extends BaseController
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
        $products = Products::withCount(['photos', 'comments'])
                              ->with('projects')
                              ->paginate(10);

        return $this->sendResponse($products, 'Products successfully Retrieved...!');   
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
            'project_id' => 'required|numeric',
            'price' => 'required|string',
            'description' => 'required|string',
            'ratings' => 'numeric',  // should given by user
            'picture' => 'mimes:jpeg,jpg,png|max:2048',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $input = $request->all();

        if ($image = $request->file('picture')) {
            Log::info("inside upload picture" .config('constants.upload_path.product'));
            
            $picture = date('YmdHis'). "." . $image->getClientOriginalExtension();

            $path = $request->file('picture')->store(config('constants.upload_path.product').$request->project_id.'/'.$request->name);

            $input['picture'] = Storage::url($path);
            
            Log::info("FILE STORED".$input['picture']);
        }
      
        $product = Products::create($input);

        return $this->sendResponse($product, 'Product added successfully...!');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $product = Products::withCount(['photos', 'comments'])
                            ->with(['projects', 'photos', 'comments', 'comments.comments', 'comments.users', 'comments.comments.users'])
                            ->latest()
                            ->find($id);

        if (is_null($product)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($product, 'Product successfully Retrieved...!');   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|between:2,100',
            'project_id' => 'numeric',
            'price' => 'string',
            'description' => 'string',
            'ratings' => 'numeric',
            'picture' => 'mimes:jpeg,jpg,png|max:2048',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $products = Products::find($id);

        if (is_null($products)) {
            return $this->sendError('Empty', [], 404);
        }

        $input = $request->all();

        if ($image = $request->file('picture')) {

            if(Storage::exists($products->picture)){
                Log::info("file exist");
                Storage::delete($products->picture);
                Log::info("file deleted");
            }

            Log::info("inside upload picture " .config('constants.upload_path.product'));
            
            $picture = date('YmdHis'). "." . $image->getClientOriginalExtension();

            $path = $request->file('picture')->store(config('constants.upload_path.product').$request->project_id.'/'.$request->name);

            $input['picture'] = Storage::url($path);
            
            Log::info("FILE STORED".$input['picture']);
        }
            
        $products->update($input);

        return $this->sendResponse($products, 'Product updated successfully...!');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $products = Products::find($id);

        if (is_null($products)) {
            return $this->sendError('Empty', [], 404);
        }

        if(Storage::exists($products->picture)){
            Storage::delete($products->picture);
        }
        
        $products->delete($request->all()); 

        return $this->sendResponse($products, 'Products deleted successfully...!');   
    }
}
