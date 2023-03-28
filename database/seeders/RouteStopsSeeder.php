<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\Route;
use App\Models\RouteStops;
use Illuminate\Database\Seeder;

class RouteStopsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $string = '[{"Format":"I25","Content":"172284201241"}]';

        $routes = Route::all();

        foreach ($routes as $key => $value) {
            for ($i = 0; $i < 5; $i++) {
                $place_id = 0;
                if ($i == 0) {
                    $place_id = $value->source_place_id;
                }

                if ($i == 4) {
                    $place_id = $value->destination_place_id;
                }

                if ($i != 0 && $i != 4) {
                    $source_place =  Place::all()->random();

                    $place_id = $source_place->id;
                }

                $exist = RouteStops::where("route_id", $value->id)
                    ->where("place_id", $place_id)
                    ->first();

                if (!$exist) {
                    $data = array(
                        'meta_data' => $string,
                        'route_id' => $value['id'],
                        'place_id' => $place_id
                    );

                    RouteStops::create($data);
                }
            }
        }
    }
}
