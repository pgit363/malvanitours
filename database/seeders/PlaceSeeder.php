<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Category;
use Illuminate\Support\Str;
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
        
        for($i = 0; $i < 100; $i++){
            DB::table("places")->insert([
                'name' => $faker->word,
                'city_id' => $faker->numberBetween(1, City::count()),
                'description' => $faker->realText(),
                'rules' => $string,
                'image_url' => $faker->randomElement(['storage/assets/places/4/Devgad Beach/nEZKix4gsHXTOQ9as6NcSLzllpVZsaJXWOyOWJLg.jpg', 'storage/assets/places/4/Devgad Beach/nEZKix4gsHXTOQ9as6NcSLzllpVZsaJXWOyOWJLg.jpg', 'storage/assets/places/4/Devgad Beach/nEZKix4gsHXTOQ9as6NcSLzllpVZsaJXWOyOWJLg.jpg']),
                'bg_image_url' => $faker->randomElement(['storage/assets/places/4/Devgad Beach/nEZKix4gsHXTOQ9as6NcSLzllpVZsaJXWOyOWJLg.jpg', 'storage/assets/places/4/Devgad Beach/nEZKix4gsHXTOQ9as6NcSLzllpVZsaJXWOyOWJLg.jpg', 'storage/assets/places/4/Devgad Beach/nEZKix4gsHXTOQ9as6NcSLzllpVZsaJXWOyOWJLg.jpg']),
                'price' => $string,
                'rating' => $faker->numberBetween(1, 5),
                'visitors_count' => $faker->numberBetween(500, 1000),
                'social_media' => $string,
                'contact_details' => $string
            ]);
        }
    }
}
