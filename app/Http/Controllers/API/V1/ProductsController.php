<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Products;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;

class ProductsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::paginate(10);
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
            'ratings' => 'numeric',
            'picture' => 'string',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }
      
        $product = Products::create($request->all());

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
        $product = Products::find($id);
        
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
            'name' => 'required|string|between:2,100',
            'project_id' => 'required|numeric',
            'price' => 'required|string',
            'description' => 'required|string',
            'ratings' => 'numeric',
            'picture' => 'string',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $products = Products::find($id);

        if (is_null($products)) {
            return $this->sendError('Empty', [], 404);
        }

        $products->update($request->all());

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

        $products->delete($request->all());

        return $this->sendResponse($products, 'Products deleted successfully...!');   
    }
}
