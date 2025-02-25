<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'phone2',
        'address',
        'facebook',
        'instagram',
        'telegram',
        'youtube',
        'linkedin',
        'tik_tok',
    ];
}
