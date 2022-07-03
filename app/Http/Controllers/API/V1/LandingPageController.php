<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Categories;
use App\Models\Projects;
use App\Models\Products;
use App\Models\Place;

class LandingPageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Categories::get();
        $projects = Projects::paginate(10);
        $places = Place::paginate(10);
        $products = Products::where('ratings', '>=', 3)->paginate(10);

        $home =  array_merge(['categories'=> $categories, 
                              'projects'=> $projects, 
                              'places'=>$places, 
                              'products'=> $products]);
        
        return $this->sendResponse($home, 'Cities successfully Retrieved...!');  
    }
}
