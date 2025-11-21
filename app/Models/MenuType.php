<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuType extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'show_prices',
    ];

    protected $casts = [
        'show_prices' => 'boolean',
    ];

    /**
     * Iga menüütüüp (lõuna, hommik, laager)
     * omab mitut kategooriat (supid, praed, jne).
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'menu_type_id')
                    ->orderBy('order_index');
    }

    /**
     * Iga menüütüüp sisaldab mitut menüüd (iga päev enda menüü).
     */
    public function menus()
    {
        return $this->hasMany(Menu::class, 'menu_type_id');
    }
}
