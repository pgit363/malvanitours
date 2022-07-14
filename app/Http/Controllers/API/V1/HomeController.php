<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as BaseController;

class HomeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function search(Request $request)
    {        
        Log::info('Showing the search results for students: '.$request->string);
        $string = $request->string;
        //all column names for dynamically exract in query
        $field = DB::getSchemaBuilder()->getColumnListing($request->table_name);
        
        $name = DB::table($request->table_name)->Where(function ($query) use($string, $field) {
             for ($i = 0; $i < count($field); $i++){
                $query->orwhere($field[$i], 'like',  '%' . $string .'%');
             }      
        })->paginate(10); //response with pagination

        Log::info("data fetched");

        return $this->sendResponse($name, 'Students successfully Retrieved...!');
    }
}
