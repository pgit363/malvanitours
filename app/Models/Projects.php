<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Hashidable;
use App\Models\Category;

class Projects extends Model
{
    use HasFactory, Hashidable, HasFactory, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'city_id',
        'category_id',
        'domain_name',
        'logo',
        'fevicon',
        'description',
        'ratings',
        'picture',
        'start_price',
        'speciality',
        'project_meta',
        'link_status',
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
        'link_status'  => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Products::class, 'project_id');
    }
    
    public function photos()
    {
        return $this->HasMany(Photos::class, 'project_id');
    }
    
    /**
     * Get the City that owns the Projects
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
