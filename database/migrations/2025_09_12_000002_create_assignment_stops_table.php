<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assignment_stops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('priority', ['high', 'normal', 'low'])->default('normal');
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            $table->unsignedInteger('sequence')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->index(['assignment_id', 'sequence']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignment_stops');
    }
};


