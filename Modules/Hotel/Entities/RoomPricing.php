<?php

namespace Modules\Hotel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Hotel\Database\Factories\RoomPricingFactory;

class RoomPricing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'room_id',
        'adult_count',
        'child_count', // default 0
        'price_per_night',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // protected static function newFactory(): RoomPricingFactory
    // {
    //     // return RoomPricingFactory::new();
    // }
}
