<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'order_index'];

    /**
     * Ühel allergeenil võib olla mitu menüü elementi.
     */
    public function items()
    {
        return $this->belongsToMany(MenuItem::class, 'allergen_menu_item');
    }
}
