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
        'is_available',
        'order_index',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'allergen_menu_item');
    }
}
