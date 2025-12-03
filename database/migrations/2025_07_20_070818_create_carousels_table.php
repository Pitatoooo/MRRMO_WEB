<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('carousels', function (Blueprint $table) {
            $table->id();
            $table->string('image');          // image filename
            $table->string('caption')->nullable(); // optional short caption
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('carousels');
    }
};
