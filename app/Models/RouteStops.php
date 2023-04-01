<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\Hashidable;

class RouteStops extends Model
{
    use Hashidable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'route_id',
        'place_id',
        'serial_no',
        'meta_data'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta_data' => 'array'
    ];
}
