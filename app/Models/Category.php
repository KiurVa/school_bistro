<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'menu_type_id',
        'name',
        'order_index',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /**
     * Kategooria kuulub ühele menüü tüübile (praad, supp jne).
     */
    public function menuType()
    {
        return $this->belongsTo(MenuType::class, 'menu_type_id');
    }

    /**
     * Kategoorial on mitu toitu (menu_items).
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class)
            ->orderBy('order_index');
    }
}
