<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Hashidable;

class Photos extends Model
{
    use  Hashidable, HasFactory, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'project_id',
        'product_id',
        'url',
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
    protected $casts = [];

    public function projects()
    {
        return $this->belongsTo(Projects::class);
    }

    public function products()
    {
        return $this->belongsTo(Products::class);
    }
}
