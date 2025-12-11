<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackgroundImage extends Model
{
    protected $fillable = [
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
