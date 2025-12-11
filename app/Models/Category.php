<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 👈 lisa see

class Category extends Model
{
    use SoftDeletes; // 👈 võimaldab soft delete’i (deleted_at veerg)

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
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
}
