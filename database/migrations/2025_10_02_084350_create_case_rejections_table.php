<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseRejectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_rejections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_num');
            $table->unsignedBigInteger('ambulance_id');
            $table->string('driver_name')->nullable();
            $table->timestamp('rejected_at')->useCurrent();
            $table->timestamps();

            $table->foreign('case_num')->references('case_num')->on('cases')->onDelete('cascade');
            $table->foreign('ambulance_id')->references('id')->on('ambulances')->onDelete('cascade');
            
            // Prevent duplicate rejections
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
        Schema::dropIfExists('case_rejections');
    }
}
