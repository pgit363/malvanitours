<?php
use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\User;
use App\Models\Place;
use App\Models\Projects;
use App\Models\Products;
use App\Models\Photos;

function currentDate()
{
    $date = date('YmdHis');
    return $date;
}

function getDbColumns($tableName)
{
   return DB::getSchemaBuilder()->getColumnListing($tableName);
}

function getData($id, $model)
{
    switch ($model) {
        case 'City':
            $data = City::find($id);
        break;
        
        case 'User':
            $data = User::find($id);
        break;
        
        case 'Projects':
            $data = Projects::find($id);
        break;

        case 'Products':
            $data = Products::find($id);
        break;

        case 'Place':
            $data = Place::find($id);
        break;

        case 'Photos':
            $data = Photos::find($id);
        break;

        default:
            # code...
        break;
    }

    return $data;
}

function getModels($path){
    $out = [];
    $results = scandir($path);
    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;
        $filename = $path . '/' . $result;
        logger($filename);
        if (is_dir($filename)) {
            $out = array_merge($out, getModels($filename));
        }else{
            $out[] = substr($filename,0,-4);
        }
    }
    return $out;
}
?>