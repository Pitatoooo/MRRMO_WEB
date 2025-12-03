<?php
// database/migrations/xxxx_xx_xx_create_officials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('officials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('image')->nullable(); // for image filename
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('officials');
    }
};