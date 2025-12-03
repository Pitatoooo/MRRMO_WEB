<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseAmbulancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_ambulances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_num');
            $table->unsignedBigInteger('ambulance_id');
            $table->boolean('driver_accepted')->default(false);
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('case_num')->references('case_num')->on('cases')->onDelete('cascade');
            $table->foreign('ambulance_id')->references('id')->on('ambulances')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['case_num', 'ambulance_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_ambulances');
    }
}
