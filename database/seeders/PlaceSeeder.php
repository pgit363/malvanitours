<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Place;
use App\Models\PlaceCategory;
use Illuminate\Support\Facades\DB;

class PlaceSeeder extends Seeder
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
            $place_category =  PlaceCategory::all()->random();
            
            DB::table("places")->insert([
                'name' => $faker->city(),
                'city_id' =>  City::all()->random()->id,
                'parent_id' => $i / 3 == 0 ? null :  Place::all()->random()->id,
                'place_category_id' => ($place_category->name == "Bus Depo") ? null : $place_category->id,
                'description' => $faker->realText(),
                'rules' => $string,
                'image_url' => $faker->imageUrl($width = 400, $height = 400),
                'bg_image_url' => $faker->imageUrl($width = 700, $height = 400),
                'price' => $string,
                'rating' => $faker->numberBetween(1, 5),
                'visitors_count' => $faker->numberBetween(500, 1000),
                'social_media' => $string,
                'contact_details' => $string,
                'latitude' => $faker->latitude(),
                'longitude' => $faker->longitude(),
                'meta_data' => $string
            ]);
        }
    }
}
