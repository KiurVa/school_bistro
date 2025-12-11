<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('background_images', function (Blueprint $table) {
            $table->string('file_path')
                  ->nullable();   // lubame igaks juhuks nulli, kuigi praktikas salvestame alati
        });
    }

    public function down(): void
    {
        Schema::table('background_images', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }
};
