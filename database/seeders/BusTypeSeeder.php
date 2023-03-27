<?php

namespace Database\Seeders;

use App\Models\BusType;
use Illuminate\Database\Seeder;

class BusTypeSeeder extends Seeder
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

        $array = array(
            [
                'type' => 'AC-Shivnery',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string
            ],
            [
                'type' => 'Semi Luxury',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string

            ],
            [
                'type' => 'Night Express',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string

            ],
            [
                'type' => 'Ordinary Express',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string
            ],
            [
                'type' => 'Day Ordinary',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string

            ],
            [
                'type' => 'AC-Sheetal',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string

            ],
            [
                'type' => 'AC-Ashwamedh',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string
            ],
            [
                'type' => 'Volvo Ac',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string

            ],
            [
                'type' => 'Shivshahi',
                'logo' =>  $faker->imageUrl($width = 400, $height = 400),
                'meta_data' => $string

            ]
        );

        foreach ($array as $key => $value) {
            $exist = BusType::where('type', $value['type'])->first();

            if (!$exist)
                BusType::create($value);
        }
    }
}
