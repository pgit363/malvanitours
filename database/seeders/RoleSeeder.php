<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Category;
use App\Models\Roles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            [
                'name' => 'SuperAdmin',
                'display_name' => 'Super Admin'
            ],
            [
                'name' => 'Admin',
                'display_name' => 'Admin'
            ],
            [
                'name' => 'User',
                'display_name' => 'User'
            ]
        );

        foreach ($array as $key => $value) {
            $exist = Roles::where([['name', $value['name']], ['display_name', $value['display_name']]])->first();

            if (!$exist)
                Roles::create($value);
        }
    }
}
