<?php
use Illuminate\Support\Facades\DB;

function currentDate()
{
    $date = date('YmdHis');
    return $date;
}

function getDbColumns($tableName)
{
   return DB::getSchemaBuilder()->getColumnListing($tableName);
}
?>