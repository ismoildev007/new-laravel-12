<?php

namespace Modules\Hotel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Hotel\Database\Factories\RoomFactory;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hotel_id',
        'name',
        'type',
        'description',
        'feature_id',
        'is_booked',
        'max_adults',
        'max_children',
        'child_allowed',
        'base_price',
        'lang',
        'lang_hash',
    ];

    protected $casts = [
        'is_booked' => 'boolean',
        'child_allowed' => 'boolean',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    // protected static function newFactory(): RoomFactory
    // {
    //     // return RoomFactory::new();
    // }
}
