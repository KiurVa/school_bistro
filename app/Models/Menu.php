<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'menu_type_id',
        'date',
        'header_line1',
        'header_line2',
        'header_line3',
        'background_image',
        'is_visible',
    ];

    protected $casts = [
        'date' => 'date',
        'is_visible' => 'boolean',
    ];

    /**
     * Menüü kuulub ühele menüü tüübile (lõuna, laager jne).
     */
    public function type()
    {
        return $this->belongsTo(MenuType::class, 'menu_type_id');
    }

    /**
     * Menüü sisaldab mitut toitu (menu_items).
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
