<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'category_id',
        'name',
        'full_price',
        'half_price',
        'included_in_main',
        'gluten_free',
        'lactose_free',
        'is_available',
        'order_index'
    ];

    protected $casts = [
        'included_in_main' => 'boolean',
        'gluten_free' => 'boolean',
        'lactose_free' => 'boolean',
        'is_available' => 'boolean',
    ];

    /**
     * Toit kuulub ühele menüüle (konkreetsel kuupäeval).
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Toit kuulub ühte kategooriasse (praed, supid jne).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
