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
        Log::info('Showing the search results for global search: '.$request->string);

        $string = $request->string;

        $field = DB::getSchemaBuilder()->getColumnListing($request->table_name);
        
        $records = DB::table($request->table_name)->Where(function ($query) use($string, $field) {
             for ($i = 0; $i < count($field); $i++){
                $query->orwhere($field[$i], 'like',  '%' . $string .'%');
             }      
        })->paginate(10); //response with pagination

        Log::info("Rcords fetched");

        return $this->sendResponse($records, 'Records successfully Retrieved...!');
    }
}
