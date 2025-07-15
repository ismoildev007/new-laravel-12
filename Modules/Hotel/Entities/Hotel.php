<?php

namespace Modules\Hotel\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Hotel\Database\Factories\HotelFactory;

class Hotel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'business_id',
        'name',
        'description',
        'slug',
        'lat',
        'long',
        'country_id',
        'region_id',
        'district_id',
        'star_rating',
        'is_active',
        'lang',
        'lang_hash',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // protected static function newFactory(): HotelFactory
    // {
    //     // return HotelFactory::new();
    // }
}
