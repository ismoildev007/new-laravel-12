<?php

namespace Modules\FileManager\App\Models;

use Illuminate\Database\Eloquent\Model;

class Fileable extends Model
{
    protected $table = 'fileables';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'file_id',
        'fileable_id',
        'fileable_type',
        'fileable_key',
    ];
}
