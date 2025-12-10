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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_type_id')
                ->constrained('menu_types')
                ->onDelete('cascade');
            $table->date('date');
            $table->string('header_line1')->nullable();
            $table->string('header_line2')->nullable();
            $table->string('header_line3')->nullable();
            $table->string('background_image')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Üks menüütüüp päevas ainult üks kord
            $table->unique(['menu_type_id', 'date']);
            $table->index(['date', 'is_visible']);
            $table->index('menu_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
