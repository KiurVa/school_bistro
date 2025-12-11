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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                ->constrained('menus')
                ->onDelete('cascade');
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('restrict');
            $table->string('name');
            $table->decimal('full_price', 8, 2);
            $table->decimal('half_price', 8, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->index(['menu_id', 'category_id', 'order_index']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
