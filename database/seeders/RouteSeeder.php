<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\Route;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
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

        for ($i = 0; $i < 100; $i++) {
            $source_place =  Place::all()->random();
            $destination_place = Place::all()->except($source_place->id)->random();

            if ($source_place->id == null || $destination_place->id == null)
                continue;

            $exist = Route::where("source_place_id", $source_place->id)
                ->where("destination_place_id", $destination_place->id)
                ->first();

            if (!$exist) {
                $data = array(
                    'source_place_id' => $source_place->id,
                    'destination_place_id' => $destination_place->id,
                    'name' => $source_place->name . " " . $destination_place->name,
                    'description' => $faker->text(),
                    'meta_data' => $string,
                    'departure_time' => $faker->time(),
                    'arrival_time' => $faker->time(),
                    'total_time' => $faker->time(),
                    'delayed_time' => $faker->time()
                );

                Route::create($data);
            }
        }
    }
}
