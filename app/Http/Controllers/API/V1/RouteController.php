<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RouteController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listroutes()
    {
        $routes = Route::withCount(['routeStops'])
            ->with([
                'sourcePlace:id,name,place_category_id',
                'sourcePlace.placeCategory:id,name,icon',
                'destinationPlace:id,name,place_category_id',
                'destinationPlace.placeCategory:id,name,icon'
            ])
            ->select('id', 'source_place_id', 'destination_place_id', 'name', 'start_time', 'end_time', 'total_time', 'delayed_time')
            ->paginate();

        return $this->sendResponse($routes, 'Routes successfully Retrieved...!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function routes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_place_id' => 'nullable|required_with:destination_place_id|exists:places,id',
            'destination_place_id' => 'nullable|required_with:source_place_id|exists:places,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), '', 200);
        }

        $routeIds = Route::whereHas('routeStops', function ($query) use ($request) {
            if ($request->has('source_place_id') && $request->has('destination_place_id')) {
                $query->where('place_id', $request->source_place_id)
                    ->whereBetween('serial_no', [
                        DB::raw("(SELECT serial_no FROM route_stops WHERE route_id = routes.id AND place_id = $request->source_place_id)"),
                        DB::raw("(SELECT serial_no FROM route_stops WHERE route_id = routes.id AND place_id = $request->destination_place_id)"),
                    ]);
            }
        })
            ->pluck('id');

        $routes = Route::with([
            'routeStops:id,serial_no,route_id,place_id,arr_time,dept_time,total_time,delayed_time',
            'routeStops.place:id,name,place_category_id',
            'routeStops.place.placeCategory:id,name,icon',
            'sourcePlace:id,name,place_category_id',
            'sourcePlace.placeCategory:id,name,icon',
            'destinationPlace:id,name,place_category_id',
            'destinationPlace.placeCategory:id,name,icon',
            'busType:id,type,logo'
        ])
            ->select('id', 'source_place_id', 'destination_place_id', 'bus_type_id', 'name', 'start_time', 'end_time', 'total_time', 'delayed_time');

        if ($request->has('source_place_id') && $request->has('destination_place_id')) {
            $routes = $routes->whereIn('id', $routeIds);
        }
        
        $routes = $routes->paginate(5);


        #need to test on both query for performance

        // $data = $request->validate([
        //     'source_place_id' => 'exists:places,id|required_with:destination_place_id',
        //     'destination_place_id' => 'exists:places,id|required_with:source_place_id',
        // ]);

        // $places = Route::with(['routeStops:id,serial_no,route_id,place_id'])
        //     ->select('id', 'name')
        //     ->whereHas('routeStops', function ($query) use ($data) {
        //         $sourcePlaceId = $data['source_place_id'];
        //         $destinationPlaceId = $data['destination_place_id'];

        //         $query->where('place_id', $sourcePlaceId)
        //             ->whereExists(function ($subquery) use ($sourcePlaceId, $destinationPlaceId) {
        //                 $subquery->select(DB::raw(1))
        //                     ->from('route_stops')
        //                     ->where('route_id', DB::raw('routes.id'))
        //                     ->where('place_id', $destinationPlaceId)
        //                     ->where('serial_no', '>', function ($subsubquery) use ($sourcePlaceId) {
        //                         $subsubquery->select('serial_no')
        //                             ->from('route_stops')
        //                             ->where('route_id', DB::raw('routes.id'))
        //                             ->where('place_id', $sourcePlaceId);
        //                     });
        //             });
        //     })
        //     ->get();

        return $this->sendResponse($routes, 'available routes successfully Retrieved...!');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        //
    }
}
