<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'description',
        'email',
        'phone',
        'address',
        'country_id',
        'region_id',
        'district_id',
        'verification_status',
        'inn',
    ];

    protected $casts = [
        'inn' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
