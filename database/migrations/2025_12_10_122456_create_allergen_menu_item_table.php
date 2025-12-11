<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('allergen_menu_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')
                ->constrained('menu_items')
                ->onDelete('cascade');
            $table->foreignId('allergen_id')
                ->constrained('allergens')
                ->onDelete('restrict');
            $table->timestamps();

            $table->unique(['menu_item_id', 'allergen_id']);

            $table->index('menu_item_id');
            $table->index('allergen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergen_menu_item');
    }
};
