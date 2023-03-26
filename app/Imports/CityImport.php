<?php

namespace App\Imports;

use App\Models\City;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class CityImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $data)
    {
        try {
            foreach ($data as $key => $value) {
                $exist = City::where('name', $value['name'])->first();
                if (!$exist) {
                    $city = new City();
                    $city->name = $value['name'];
                    $city->tag_line = $value['tag_line'];
                    $city->description = $value['description'];
                    $city->save();
                }
            }
        } catch (\Throwable $th) {
            throw $th->getMessage();
        }
    }
}
