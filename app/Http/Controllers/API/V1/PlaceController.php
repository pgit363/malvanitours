<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Place;
use Illuminate\Http\Request;

use App\Http\Controllers\BaseController as BaseController;

class PlaceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $places = Place::withCount(['photos', 'comments'])
            ->with('photos', 'city:id,name,image_url')
            ->paginate(10);
        return $this->sendResponse($places, 'Places successfully Retrieved...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //write visitors count update here by one
        $place = Place::withCount(['photos', 'comments'])
            ->with(['photos', 'city', 'comments', 'comments.comments', 'comments.users', 'comments.comments.users'])
            ->find($id);

        // $city = City::withCount(['projects', 'places', 'photos', 'comments'])
        //             ->with(['comments', 'comments.comments', 'comments.users', 'comments.comments.users', 'places', 'photos'])
        //             ->latest()
        //             ->limit(10)
        //             ->find($id);

        if (is_null($place)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($place, 'Place successfully Retrieved...!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchPlace(Request $request)
    {
        $data = $request->validate([
            'search' => 'sometimes|nullable|string|alpha|max:255',
            'type' => 'sometimes|nullable|string|max:255|in:bus',
        ]);

        $places = Place::withCount(['photos', 'comments'])
            ->with(['photos', 'city:id,name,image_url', 'placeCategory:id,name,icon'])
            ->when($data['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($data['type'] ?? null && $data['type'] == 'bus', function ($query) {
                $query->whereHas('placeCategory', function ($query) {
                    $query->whereIn('name', ['Bus Stop', 'Bus Depo']);
                });
            })
            ->select('id', 'name', 'city_id', 'parent_id', 'place_category_id', 'image_url', 'bg_image_url', 'visitors_count')
            ->get();

        return $this->sendResponse($places, 'Stops successfully Retrieved...!');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stops()
    {
        $places = Place::withCount(['photos', 'comments'])
            ->with(['photos', 'city:id,name,image_url', 'placeCategory:id,name,icon'])
            ->whereHas('placeCategory', function ($query) {
                $query->whereIn('name', ['Bus Stop', 'Bus Depo']);
            })
            ->select('id', 'name', 'city_id', 'parent_id', 'place_category_id', 'image_url', 'bg_image_url', 'visitors_count')
            ->paginate(10);

        return $this->sendResponse($places, 'Stops successfully Retrieved...!');
    }
}
