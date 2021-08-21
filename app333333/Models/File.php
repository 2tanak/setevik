<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'disk',
        'dir',
        'path',
        'size',
        'mime_type',
        'name',
        'original_name',
        'description',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}
